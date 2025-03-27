<?php
include('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
    header("location: ../index.php");
}

$query4 = "SELECT * from farmerlogin where email='$user_check'";
$ses_sq4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($ses_sq4);
$para1 = $row4['farmer_id'];
$para2 = $row4['farmer_name'];

// Instead of using database state, we'll use a session variable for state
if(!isset($_SESSION['farmer_state'])) {
    $_SESSION['farmer_state'] = 'Maharashtra'; // Default state
}
$para3 = $_SESSION['farmer_state'];

// Handle state change if submitted
if(isset($_POST['change_state'])) {
    $_SESSION['farmer_state'] = $_POST['new_state'];
    $para3 = $_SESSION['farmer_state'];
}
?>

<!DOCTYPE html>
<html>
<?php require('fheader.php'); ?>

<body class="bg-white" id="top">
    <?php include('fnav.php'); ?>

    <section class="section section-shaped section-lg">
        <div class="shape shape-style-1 shape-primary">
            <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
        </div>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header bg-gradient-success">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h2 class="text-white mb-0">Government Schemes for Farmers</h2>
                                </div>
                                <div class="col-4 text-right">
                                    <form method="post" class="d-inline-flex align-items-center">
                                        <select name="new_state" class="form-control form-control-sm mr-2" style="width: auto;">
                                            <option value="Maharashtra" <?php echo ($para3 == 'Maharashtra') ? 'selected' : ''; ?>>Maharashtra</option>
                                            <option value="Karnataka" <?php echo ($para3 == 'Karnataka') ? 'selected' : ''; ?>>Karnataka</option>
                                            <option value="Tamil Nadu" <?php echo ($para3 == 'Tamil Nadu') ? 'selected' : ''; ?>>Tamil Nadu</option>
                                            <option value="Punjab" <?php echo ($para3 == 'Punjab') ? 'selected' : ''; ?>>Punjab</option>
                                            <option value="Uttar Pradesh" <?php echo ($para3 == 'Uttar Pradesh') ? 'selected' : ''; ?>>Uttar Pradesh</option>
                                        </select>
                                        <button type="submit" name="change_state" class="btn btn-sm btn-white">
                                            Change State
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 80vh; overflow-y: auto;">
                            <!-- Central Schemes Section -->
                            <div class="scheme-section mb-5">
                                <h3 class="text-primary"><i class="fas fa-flag"></i> Central Government Schemes</h3>
                                <div class="row">
                                    <!-- PM-KISAN -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h4 class="mb-0">PM-KISAN</h4>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted">Pradhan Mantri Kisan Samman Nidhi - Direct income support for farmers</p>
                                                <h5>Benefits:</h5>
                                                <ul>
                                                    <li>₹6,000 per year to eligible farmer families</li>
                                                    <li>Paid in three installments of ₹2,000 each</li>
                                                    <li>Direct transfer to bank accounts</li>
                                                </ul>
                                                <h5>Eligibility:</h5>
                                                <ul>
                                                    <li>All landholding farmer families</li>
                                                    <li>Small and marginal farmers</li>
                                                    <li>Subject to certain exclusion criteria</li>
                                                </ul>
                                                <a href="https://pmkisan.gov.in/" target="_blank" class="btn btn-primary mt-3">
                                                    <i class="fas fa-external-link-alt"></i> Apply Now
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PMFBY -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 border-success">
                                            <div class="card-header bg-success text-white">
                                                <h4 class="mb-0">Pradhan Mantri Fasal Bima Yojana</h4>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted">Comprehensive crop insurance scheme</p>
                                                <h5>Benefits:</h5>
                                                <ul>
                                                    <li>Insurance coverage for crop loss</li>
                                                    <li>Protection against natural calamities</li>
                                                    <li>Low premium rates for farmers</li>
                                                </ul>
                                                <h5>Coverage:</h5>
                                                <ul>
                                                    <li>Food crops, oilseeds, and annual commercial crops</li>
                                                    <li>Post-harvest losses</li>
                                                    <li>Prevented sowing coverage</li>
                                                </ul>
                                                <a href="https://pmfby.gov.in/" target="_blank" class="btn btn-success mt-3">
                                                    <i class="fas fa-external-link-alt"></i> Learn More
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PKVY -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 border-info">
                                            <div class="card-header bg-info text-white">
                                                <h4 class="mb-0">Paramparagat Krishi Vikas Yojana</h4>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted">Promotion of organic farming practices</p>
                                                <h5>Benefits:</h5>
                                                <ul>
                                                    <li>Financial assistance for organic conversion</li>
                                                    <li>Training on organic farming</li>
                                                    <li>Marketing support for organic products</li>
                                                </ul>
                                                <h5>Support Provided:</h5>
                                                <ul>
                                                    <li>₹50,000 per hectare for 3 years</li>
                                                    <li>Organic certification assistance</li>
                                                    <li>Formation of farmer clusters</li>
                                                </ul>
                                                <button class="btn btn-info mt-3" onclick="showSchemeDetails('PKVY')">
                                                    <i class="fas fa-info-circle"></i> More Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- AIF -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 border-warning">
                                            <div class="card-header bg-warning text-white">
                                                <h4 class="mb-0">Agriculture Infrastructure Fund</h4>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted">Financial support for agri-infrastructure projects</p>
                                                <h5>Benefits:</h5>
                                                <ul>
                                                    <li>Long term debt financing</li>
                                                    <li>Interest subvention of 3%</li>
                                                    <li>Credit guarantee coverage</li>
                                                </ul>
                                                <h5>Eligible Projects:</h5>
                                                <ul>
                                                    <li>Warehouse and cold storage facilities</li>
                                                    <li>Post-harvest management projects</li>
                                                    <li>Community farming assets</li>
                                                </ul>
                                                <a href="https://agriinfra.dac.gov.in/" target="_blank" class="btn btn-warning mt-3">
                                                    <i class="fas fa-external-link-alt"></i> Apply Online
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- State Specific Schemes -->
                            <div class="scheme-section">
                                <h3 class="text-primary"><i class="fas fa-map-marker-alt"></i> <?php echo $para3; ?> State Schemes</h3>
                                <div class="row" id="stateSchemes">
                                    <!-- State schemes will be loaded dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require("footer.php"); ?>

    <style>
    .card {
        transition: transform 0.2s;
        margin-bottom: 20px;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .scheme-section {
        position: relative;
        padding: 20px;
        border-radius: 10px;
        background: #fff;
    }
    .scheme-section h3 {
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }
    .card-header h4 {
        font-size: 1.2rem;
    }
    .btn-apply {
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
    }
    /* Added styles for better scrolling and layout */
    .section {
        min-height: 100vh;
        padding-top: 80px; /* Adjust based on your navbar height */
    }
    .card-body {
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
    }
    .card-body::-webkit-scrollbar {
        width: 8px;
    }
    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .card-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .card-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    /* Fix for mobile responsiveness */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 10px;
        }
        .card-body {
            padding: 15px;
        }
        .col-md-6, .col-md-4 {
            padding: 5px;
        }
    }
    </style>

    <script>
    $(document).ready(function() {
        // Load state-specific schemes based on user's state
        const userState = "<?php echo $para3; ?>";
        
        // Simulated AJAX call - Replace with actual API endpoint
        setTimeout(() => {
            const stateSchemes = getStateSchemes(userState);
            $('#stateSchemes').html(stateSchemes);
        }, 1000);
    });

    function getStateSchemes(state) {
        const schemes = {
            "Maharashtra": [
                {
                    title: "Nanaji Deshmukh Krishi Sanjivani Yojana",
                    description: "Climate-resilient agriculture practices",
                    benefits: [
                        "Support for climate-resilient farming",
                        "Water conservation assistance",
                        "Sustainable agriculture training"
                    ],
                    eligibility: ["All farmers in selected districts", "Priority to small farmers"],
                    color: "info"
                },
                {
                    title: "Chief Minister's Solar Agriculture Pump Scheme",
                    description: "Solar pump installation support",
                    benefits: [
                        "Subsidized solar pumps",
                        "Reduced electricity costs",
                        "Environmental benefits"
                    ],
                    eligibility: ["Land-owning farmers", "Areas with adequate sunlight"],
                    color: "warning"
                }
            ],
            "Karnataka": [
                {
                    title: "Krishi Bhagya Scheme",
                    description: "Irrigation and rainwater harvesting support",
                    benefits: ["Polyhouse construction", "Water storage structures", "Drip irrigation"],
                    eligibility: ["Small and marginal farmers", "Rainfed area farmers"],
                    color: "success"
                }
            ],
            // Add more states here
        };

        let html = '';
        const stateSchemes = schemes[state] || [];

        if (stateSchemes.length === 0) {
            return '<div class="col-12"><div class="alert alert-info">No specific schemes found for your state. Please check with your local agriculture office.</div></div>';
        }

        stateSchemes.forEach(scheme => {
            html += `
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-${scheme.color}">
                        <div class="card-header bg-${scheme.color} text-white">
                            <h4 class="mb-0">${scheme.title}</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">${scheme.description}</p>
                            <h5>Benefits:</h5>
                            <ul>
                                ${scheme.benefits.map(benefit => `<li>${benefit}</li>`).join('')}
                            </ul>
                            <h5>Eligibility:</h5>
                            <ul>
                                ${scheme.eligibility.map(criteria => `<li>${criteria}</li>`).join('')}
                            </ul>
                            <button class="btn btn-${scheme.color} mt-3" onclick="showSchemeDetails('${scheme.title}')">
                                <i class="fas fa-info-circle"></i> More Details
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        return html;
    }

    function showSchemeDetails(schemeTitle) {
        // Implement a modal or redirect to show more details about the scheme
        alert('Detailed information about ' + schemeTitle + ' will be shown here.');
    }
    </script>
</body>
</html> 