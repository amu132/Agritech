<?php
include('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
    header("location: ../index.php");
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
                            <h2 class="text-white mb-0">Recommended Crop Insurance Plans</h2>
                        </div>
                        <div class="card-body" style="max-height: 80vh; overflow-y: auto;">
                            <div class="row">
                                <!-- AgriProtect -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-success">
                                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                            <h4 class="mb-0">AgriProtect</h4>
                                            <span class="badge badge-light">Premium: ₹5,000</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <div>
                                                    <h6 class="text-muted">Provider</h6>
                                                    <p class="mb-0">Bharat Agro Insurance</p>
                                                </div>
                                                <div class="text-right">
                                                    <h6 class="text-muted">Coverage</h6>
                                                    <h4 class="text-success mb-0">80%</h4>
                                                </div>
                                            </div>
                                            <div class="alert alert-success">
                                                <strong>Potential Claim: </strong>₹24,000
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6>Eligible Crops</h6>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fas fa-seedling text-success mr-2"></i>Wheat</li>
                                                        <li><i class="fas fa-seedling text-success mr-2"></i>Rice</li>
                                                        <li><i class="fas fa-seedling text-success mr-2"></i>Corn</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Coverage Period</h6>
                                                    <p class="text-muted">2025-03-01 to 2026-03-01</p>
                                                </div>
                                            </div>
                                            <h6>Payout Conditions</h6>
                                            <div class="mb-3">
                                                <span class="badge badge-pill badge-success mr-2">Drought</span>
                                                <span class="badge badge-pill badge-success mr-2">Excess rainfall</span>
                                                <span class="badge badge-pill badge-success">Pest infestation</span>
                                            </div>
                                            <button class="btn btn-success btn-block mt-3" onclick="showInsuranceDetails('AgriProtect')">
                                                <i class="fas fa-calculator mr-2"></i>Calculate Premium
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add similar cards for other insurance plans -->
                                <!-- CropSure -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-primary">
                                        <!-- Similar structure as above with different data -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require("footer.php"); ?>

    <script>
    function showInsuranceDetails(planName) {
        const insurancePlans = {
            "AgriProtect": {
                name: "AgriProtect",
                provider: "Bharat Agro Insurance",
                premium: "₹5,000",
                coverage: "80%",
                potential_claim: "₹24,000",
                crops: ["Wheat", "Rice", "Corn"],
                location: "Maharashtra",
                period: "2025-03-01 to 2026-03-01",
                payout_conditions: [
                    "Drought",
                    "Excess rainfall",
                    "Pest infestation"
                ]
            },
            // Add other plans here
        };

        const plan = insurancePlans[planName];
        if (!plan) return;

        // Create and show modal with plan details
        const modalHtml = `
            <div class="modal fade" id="insuranceModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">${plan.name} - Insurance Details</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Premium Calculator</h6>
                                    <div class="form-group">
                                        <label>Select Crop Area (Acres)</label>
                                        <input type="number" class="form-control" id="cropArea" min="1" value="1">
                                    </div>
                                    <div class="form-group">
                                        <label>Select Crop Type</label>
                                        <select class="form-control" id="cropType">
                                            ${plan.crops.map(crop => `<option value="${crop}">${crop}</option>`).join('')}
                                        </select>
                                    </div>
                                    <div class="alert alert-info">
                                        Estimated Premium: <span id="calculatedPremium">${plan.premium}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Coverage Details</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Coverage:</strong> ${plan.coverage}</li>
                                        <li><strong>Max Claim:</strong> ${plan.potential_claim}</li>
                                        <li><strong>Period:</strong> ${plan.period}</li>
                                    </ul>
                                    <h6>Eligible Locations</h6>
                                    <p>${plan.location}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success">Apply Now</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        $('#insuranceModal').remove();
        
        // Add new modal to body
        $('body').append(modalHtml);
        
        // Show modal
        $('#insuranceModal').modal('show');

        // Add calculator functionality
        $('#cropArea').on('input', function() {
            const area = $(this).val();
            const basePremium = parseInt(plan.premium.replace(/[^0-9]/g, ''));
            const calculated = basePremium * area;
            $('#calculatedPremium').text(`₹${calculated.toLocaleString()}`);
        });
    }
    </script>
</body>
</html> 