<!-- Page Content -->
<div class="container">

    <!-- Page Heading -->
    <h1 class="my-4">Settings
    <small></small>
    </h1>

    <!-- settings form -->
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 portfolio-item"></div>
        <div class="col-lg-6 col-md-8 col-sm-12 portfolio-item fileViewDiv">
            <form action='<?php echo site_url('Login/settingsSub'); ?>' method='POST'>
                <input type="hidden" name="nonce" readonly value="<?php echo $nonce ?>">   
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="fl1Set" name="flag1" id="flag1"/>
                    <label class="form-check-label" for="flag1">Delete all shared links</label>
                    <br/>
                    <br/>
                </div> 
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="flSet" name="flag" id="flag" <?php 
                        if($flagStatus==0){
                            echo 'checked';
                        }
                    ?>>
                    <label class="form-check-label" for="flag">Make storage public</label>
                    <br/>
                    <br/>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">New password</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword"/>
                    <small id="newPassword" class="form-text text-muted">Leave empty if you don't won't to change password.</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="oldPassword" name="oldPassword"/>
                    <small id="newPassword" class="form-text text-muted">Enter current password.</small>
                </div>
                <button type="submit" class="btn btn-primary">Save changes</button>
                <br/>
                <br/>
                <p class="<?php 
                    if($flagError=='Wrong password'){
                        echo 'text-danger';
                    }
                    else{
                        if($flagError=='Changes saved'){
                            echo 'text-success';
                        }
                    }
                ?>"><?php echo $flagError; ?></p>
            </form>
        </div> 
    </div>

</div>