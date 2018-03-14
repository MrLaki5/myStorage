    <!-- Page Content -->
    <div class="container">

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

      <!-- Upload file and create new folder -->
      <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">
                <div class="form-group">
                    <form action='<?php echo site_url('DataExplorer/UploadFile'); ?>' method='POST' enctype='multipart/form-data'>
                        <small id="userFile" class="form-text text-muted">Upload file to current folder.</small>
                        <input type='file' name='userFile' class="form-control-file" id="userFile">
                        <input type='submit' name='upload_btn' value='Upload'>
                    </form>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">
                <div class="form-group">
                    <?php echo form_open('DataExplorer/CreateDirectory'); ?>
                        <small id="file1Help" class="form-text text-muted">Create folder.</small>
                        <input type="text" name="FolderName" id="file1Help">
                        <input type="submit" value="Create">
                    </form>
                </div>
            </div>
      </div>

      <!-- files content part -->
      <div class="row">
        <?php 
            //iterate through all files in dir
            foreach($files as &$file){
                //step over . and ..
                if($file =='.' || $file=='..'){
                    continue;
                }
                //echo html tags for file
                $link=site_url('DataExplorer/index/' . $file);
                echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">';
                echo '<div class="card h-100 heightBaner">';
                echo '<a href="' . $link . '"><img class="card-img-top" src="';
                //image for file in explorer
                $fullFileName=$this->session->userdata('curr_path') . $PARSE_SIGN . $file;
                if(is_dir($fullFileName)){
                    echo base_url().'assets/images/folder.png';
                }
                else{
                    $file_extension= pathinfo($fullFileName, PATHINFO_EXTENSION);
                    switch($file_extension){
                        case "bnp":
                        case "png":
                        case "jpeg":
                        case "jpg":
                            echo base_url().'assets/images/image.png';
                            break;
                        case "mp4":
                            echo base_url().'assets/images/video.png';
                            break;
                        case "mp3":
                            echo base_url().'assets/images/audio.png';
                            break;
                        default:
                            echo base_url().'assets/images/file.png';
                    }
                }
                //remove and download links
                echo '" alt=""></a>';
                echo '<div class="card-body textInDiv">';
                echo '<h4 class="card-title">';             
                echo '<a href="' . $link . '" class="">' . $file . '</a> ';
                echo '</h4>';
                echo '<p class="card-text">';
                echo 'Size: ' . filesize($fullFileName) . ' bytes';
                echo '</p>';
                $linkD=site_url('DataExplorer/DownloadFile/' . $file);
                echo ' <a href="' . $linkD . '">Download</a> ';
                $linkR=site_url('DataExplorer/RemoveFile/' . $file);
                echo ' <a href="' . $linkR . '">Remove</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        ?>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->
