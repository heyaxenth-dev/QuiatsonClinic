<?php ?>
<div class="modal fade" id="labSlipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Laboratory Request Slip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="lab-slip-content" class="p-3" style="border:1px solid #ccc;">
                    <div class="text-center mb-3">
                        <div class="fw-bold" style="font-size:18px;">MEDICHEALTH DIANOSTIC LABORATORY</div>
                        <div class="fw-bold" style="font-size:16px;">Laboratory Request Slip</div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <div>LABORATORY #: <span id="ls-labno">-</span></div>
                        <div>Date: <span id="ls-date">-</span></div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">Name: <span id="ls-name">-</span></div>
                        <div class="col-md-3">Gender: <span id="ls-gender">-</span></div>
                        <div class="col-md-3">Age: <span id="ls-age">-</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">Civil Status: <span id="ls-civil">-</span></div>
                        <div class="col-md-6">Address: <span id="ls-address">-</span></div>
                    </div>
                    <div class="mb-3">Email Address: <span id="ls-email">-</span></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="fw-bold">BLOOD CHEMISTRY</div>
                            <div><label><input type="checkbox" class="ls-test" value="FBS"> FBS</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="RBS"> RBS</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="BUN"> BUN</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="Creatinine"> Creatinine</label>
                            </div>
                            <div><label><input type="checkbox" class="ls-test" value="Uric Acid"> Uric Acid</label>
                            </div>
                            <div><label><input type="checkbox" class="ls-test" value="Cholesterol"> Cholesterol</label>
                            </div>
                            <div><label><input type="checkbox" class="ls-test" value="Triglycerides">
                                    Triglycerides</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="Lipid Profile"> Lipid
                                    Profile</label></div>

                            <div class="fw-bold mt-3">MICROSCOPY</div>
                            <div><label><input type="checkbox" class="ls-test" value="Urinalysis"> Urinalysis</label>
                            </div>
                            <div><label><input type="checkbox" class="ls-test" value="Fecalysis"> Fecalysis</label>
                            </div>

                            <div class="fw-bold mt-3">OTHERS:</div>
                            <div><label><input type="checkbox" class="ls-test" value="Others"> <span
                                        style="display:inline-block;min-width:150px;border-bottom:1px solid #000;">&nbsp;</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fw-bold">HEMATOLOGY</div>
                            <div><label><input type="checkbox" class="ls-test" value="ABO/Rh Typing"> ABO/Rh
                                    Typing</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="CBC"> CBC</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="CBC w/Platelet Count"> CBC
                                    w/Platelet Count</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="ESR (Westergren)"> ESR
                                    (Westergren)</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="High/Hct"> High/Hct</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="Platelet Count"> Platelet
                                    Count</label></div>

                            <div class="fw-bold mt-3">SEROLOGY</div>
                            <div><label><input type="checkbox" class="ls-test" value="ASO Titer"> ASO Titer</label>
                            </div>
                            <div><label><input type="checkbox" class="ls-test" value="Pregnancy"> Pregnancy</label>
                            </div>
                            <div><label><input type="checkbox" class="ls-test" value="RPR/VDRL"> RPR/VDRL</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="HBsAg"> HBsAg</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="Dengue NS1, IgG, IgM"> Dengue NS1,
                                    IgG, IgM</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="TSH"> TSH</label></div>
                            <div><label><input type="checkbox" class="ls-test" value="T3"> T3</label></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="btn-print-lab-slip" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>