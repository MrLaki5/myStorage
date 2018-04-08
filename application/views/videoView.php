<!-- Page Content -->
<div class="container" style="height: 100%;">

    <!-- Page Heading -->
    <h1 class="my-4">Data explorer
    <small><?php echo $relativePath; ?></small>
    </h1>

    <?php
        //check if back option is needed (if dir is not cloud root)
        if(!$isRoot){ 
            $linkBack=site_url('DataExplorer/BackDirection');
            echo '<div class="row">';
            echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">';
            echo '<a href="' . $linkBack . '">Back</a>';
            echo '</div>';
            echo '</div>';
        }
    ?>

    <div class="row">

        <?php 
            //share file as link part
            if(!$this->session->has_userdata('root_link')){

                echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">
                <div class="form-group">
                <small id="userFile" class="form-text text-muted">Share current file.</small>';


                $isShared=0;
                $txt= $this->session->userdata('curr_path') . "\n";
                $destPath= FCPATH . 'confFiles' . $PARSE_SIGN . 'links.php';
                $fh = fopen($destPath,'r');
                $line = fgets($fh);
                while($line = fgets($fh)){
                    $line1=substr($line, 21);
                    if($line1==$txt){
                        $isShared=1;
                        break;
                    }
                }
                fclose($fh);

                if($isShared==0){
                    $linkC=site_url('DataExplorer/createShareLink');
                    echo ' <a href="' . $linkC . '">Create link</a> ';
                }
                else{
                    $linkS=site_url('DataExplorer/showShareLink');
                    echo ' <a href="' . $linkS . '">Show link</a><br/> ';
                    $linkC=site_url('DataExplorer/deleteShareLink');
                    echo ' <a href="' . $linkC . '">Delete link</a> ';
                }

                echo '</div>';
                echo '</div>';
            }

            $linkDown=site_url('DataExplorer/DownloadFile/');
            echo    '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">
                        <div class="form-group">';
            echo        '   <small class="form-text text-muted">Download file.</small>';
            echo        '   <a href="' . $linkDown . '">Download</a><br/> '; 
            echo        '</div>
                    </div>';

        ?>

    </div>

    <!-- video content part -->
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 portfolio-item"></div>
        <div class="col-lg-6 col-md-8 col-sm-12 portfolio-item">
            <div class="thumbnail">
                <?php 
                    //get name of video
                    $file_name= pathinfo($this->session->userdata('curr_path'), PATHINFO_FILENAME);
                    //get curr date
                    $curr_date= $date = date('Y-m-d');
                    //set up video name date part
                    $videoName= hash('md2', $curr_date);
                    $videoName .= "_";
                    //set up video name, real name part
                    $videoName .= hash('md2', $file_name);
                    //get extension
                    $file_extension= pathinfo($this->session->userdata('curr_path'), PATHINFO_EXTENSION);
                ?>
                <?php 
                    if($fileExtension=='mov') {
                        echo '<video width="100%" controls ';
                        echo 'src="';
                        echo base_url() . 'video/' . $videoName . '.' . $file_extension;
                        echo '">Your browser does not support HTML5 video.</video>';
                    } 
                    else{
                        echo '<video width="100%" controls>';
                        echo '<source src="';
                        echo base_url() . 'video/' . $videoName . '.' . $file_extension;
                        echo '" type="video/';
                        echo $file_extension;
                        echo '">';
                        echo 'Your browser does not support HTML5 video.';
                        echo '</video>';
                    } 
                ?>
            </div>
        </div> 
    </div> 
     
</div>