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
                ?>              
                <video width="400" controls>
                    <source src="<?php echo base_url() . 'video/' . $videoName . '.' . $fileExtension; ?>" type="video/<?php echo $fileExtension; ?>">
                    Your browser does not support HTML5 video.
                </video>
            </div>
        </div> 
    </div> 
     
</div>
    


