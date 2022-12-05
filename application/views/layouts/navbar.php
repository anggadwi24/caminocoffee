<!-- Header -->
<div class="header">
			
            <!-- Logo -->
            <div class="header-left">
                <a href="<?= base_url()?>" class="logo">
                    <img src="<?= logo()?>" width="40" height="40" alt="">
                </a>
            </div>
            <!-- /Logo -->
            
            <a id="toggle_btn" href="javascript:void(0);">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>
            
            <!-- Header Title -->
            <div class="page-title-box">
                <h3><?= title() ?></h3>
            </div>
            <!-- /Header Title -->
            
            <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>
            
            <!-- Header Menu -->
            <ul class="nav user-menu">
            
            
                <?php 
                    
                    $id = $this->session->userdata['isLog']['id'];
			        $role = $this->session->userdata['isLog']['role'];
                    if($role == 'hrd'){
                        $child = $this->model_app->view_where('hrd',array('users_id'=>$id))->row();

                    }else{
                        $child = $this->model_app->view_where('pegawai',array('users_id'=>$id))->row();
                    }
                ?>
                
            
                

                <li class="nav-item dropdown has-arrow main-drop">
                    <a href="javascript:void(0)" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <span class="user-img">
                        <?php 
                            if( $child->photo != ''){
                                if(file_exists('upload/user/'.$child->photo) ){
                                    echo '<img src="'.base_url('upload/user/'.$child->photo).'" alt="'.$child->name.'">';

                                }else{
                                    echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$child->name.'">';
                                }
                                
                            }else{
                                echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$child->name.'">';
                            }
                        ?>
                           
                        
                        <span><?= $child->name?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= base_url('profile')?>">Profil</a>
                        
                        <a class="dropdown-item" href="<?= base_url('logout')?>">Logout</a>
                    </div>
                </li>
            </ul>
            <!-- /Header Menu -->
            
            <!-- Mobile Menu -->
            <div class="dropdown mobile-user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile.html">Profil</a>
                    
                    <a class="dropdown-item" href="login.html">Logout</a>
                </div>
            </div>
            <!-- /Mobile Menu -->
            
        </div>
        <!-- /Header -->