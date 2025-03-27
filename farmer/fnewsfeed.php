<?php
include('fsession.php');
include('config.php'); // Include the configuration file

ini_set('memory_limit', '-1');

if (!isset($_SESSION['farmer_login_user'])) {
    header("location: ../index.php");
} // Redirecting To Home Page

$query4 = "SELECT * from farmerlogin where email='$user_check'";
$ses_sq4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($ses_sq4);
$para1 = $row4['farmer_id'];
$para2 = $row4['farmer_name'];

define('NEWS_API_KEY', 'eeb809ccada1415896a76735c42a097a');

if (!defined('NEWS_API_KEY') || strlen(NEWS_API_KEY) != 32) {
    error_log("Invalid News API Key configuration");
}

?>

<!DOCTYPE html>
<html>
<?php require('fheader.php'); ?>

<body class="bg-white" id="top">

<?php include('fnav.php'); ?>

<section class="section section-shaped section-lg">
    <div class="shape shape-style-1 shape-primary">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <span class="badge badge-danger badge-pill mb-3">News</span>
            </div>
        </div>

        <div class="row row-content">
            <div class="col-md-12 mb-3">
                <div class="card text-white bg-gradient-secondary mb-3">
                    <div class="card-header">
                        <span class="text-warning display-4">Indian Agriculture News</span>
                    </div>

                    <div class="card-body text-dark">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered bg-gradient-white text-center" id="newsTable">
                                <thead>
                                    <tr class="font-weight-bold text-default">
                                        <th><center>Image</center></th>
                                        <th><center>Title</center></th>
                                        <th><center>Author</center></th>
                                        <th><center>Published</center></th>
                                        <th><center>Visit</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    error_reporting(E_ERROR | E_PARSE);

                                    $url = "https://newsapi.org/v2/everything?" . http_build_query([
                                        'q' => '(agriculture OR farming OR crops) AND (India OR Indian)',
                                        'language' => 'en',
                                        'sortBy' => 'publishedAt',
                                        'pageSize' => 15,
                                        'domains' => 'indianexpress.com,timesofindia.indiatimes.com,thehindu.com,hindustantimes.com,ndtv.com,economictimes.indiatimes.com',
                                        'apiKey' => NEWS_API_KEY
                                    ]);

                                    try {
                                        $ch = curl_init();
                                        curl_setopt_array($ch, [
                                            CURLOPT_URL => $url,
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_SSL_VERIFYPEER => false,
                                            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                                            CURLOPT_TIMEOUT => 30,
                                            CURLOPT_HTTPHEADER => [
                                                'X-Api-Key: ' . NEWS_API_KEY,
                                                'Accept: application/json'
                                            ]
                                        ]);

                                        $response = curl_exec($ch);
                                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                        curl_close($ch);

                                        if ($httpCode !== 200) {
                                            throw new Exception("HTTP Error: " . $httpCode);
                                        }

                                        $newsdata = json_decode($response);

                                        if (!$newsdata || !isset($newsdata->articles) || empty($newsdata->articles)) {
                                            echo '<tr><td colspan="5" class="text-center">';
                                            echo '<div class="alert alert-warning" role="alert">';
                                            echo '<i class="fas fa-exclamation-triangle mr-2"></i> No agriculture news available at the moment.';
                                            echo '</div>';
                                            echo '</td></tr>';
                                        } else {
                                            foreach ($newsdata->articles as $news) {
                                                $image = $news->urlToImage ?: '../assets/img/default-news.jpg';
                                                $author = htmlspecialchars($news->author ?: 'Unknown');
                                                $title = htmlspecialchars($news->title);
                                                $publishDate = date("d M Y, H:i", strtotime($news->publishedAt));
                                                ?>
                                                <tr>
                                                    <td><img class="img img-thumbnail" src="<?php echo $image; ?>" alt="News thumbnail" width="100px" onerror="this.src='../assets/img/default-news.jpg'"></td>
                                                    <td class="text-wrap text-left"><?php echo $title; ?></td>
                                                    <td class="text-wrap"><?php echo $author; ?></td>
                                                    <td><?php echo $publishDate; ?></td>
                                                    <td>
                                                        <a href="<?php echo $news->url; ?>" class="btn btn-sm btn-info text-white" target="_blank">
                                                            <i class="fas fa-external-link-alt"></i> Read More
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    } catch (Exception $e) {
                                        echo '<tr><td colspan="5" class="text-center">';
                                        echo '<div class="alert alert-danger" role="alert">';
                                        echo '<i class="fas fa-exclamation-circle mr-2"></i> Unable to load news feed: ' . htmlspecialchars($e->getMessage());
                                        echo '</div>';
                                        echo '</td></tr>';
                                        error_log("News API Error: " . $e->getMessage());
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require("footer.php"); ?>
<script>
$(document).ready(function() {
    // Initialize DataTable with configuration
    $('#newsTable').DataTable({
        "pageLength": 5,
        "order": [[3, "desc"]], // Sort by published date
        "language": {
            "lengthMenu": "Show _MENU_ news items",
            "search": "Search news:",
            "paginate": {
                "next": "Next →",
                "previous": "← Previous"
            }
        },
        "autoWidth": false,
        "responsive": true,
        "stateSave": true
    });
});
</script>
</body>
</html>
