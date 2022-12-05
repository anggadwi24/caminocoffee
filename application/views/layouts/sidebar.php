<div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<?php
							 $role = $this->session->userdata['isLog']['role'];
							 if($role == 'hrd'){

							 
						?>
						<ul>
							<li class="menu-title"> 
								<span>Main</span>
							</li>
							<li class="active">
								<a href="<?= base_url() ?>" ><i class="la la-dashboard"></i> <span> Dashboard</span> </a>
								
							</li>
							
							<li class="menu-title"> 
								<span>Pegawai</span>
							</li>
							<li >
								<a href="<?= base_url('absensi')?>" class="active"><i class="la la-calendar"></i> <span> Absensi</span> </a>
								
							</li>
							
							<li >
								<a href="<?= base_url('pegawai')?>" class="active"><i class="la la-user"></i> <span> Pegawai</span> </a>
								
							</li>
							<li >
								<a href="#" ><i class="la la-money"></i> <span> Gaji</span> </a>
								
							</li>
							<li >
								<a href="<?= base_url('schedule')?>" ><i class="la la-calendar"></i> <span>Jadwal</span> </a>
								
							</li>
							<li class="menu-title"> 
								<span>HRD</span>

							</li>
							
							<li> 
								<a href="<?= base_url('user')?>"><i class="la la-users"></i> <span>HRD</span></a>
							</li>
							<li class="menu-title">
								<span>Konfigurasi</span>
							</li>
							<li >
								<a href="<?= base_url('shift')?>"><i class="la la-calendar"></i> <span> Shift</span> </a>
								
							</li>
							
						</ul>
						<?php }else{ ?>
							<ul>
							<li class="menu-title"> 
								<span>Main</span>
							</li>
							<li class="active">
								<a href="<?= base_url() ?>" ><i class="la la-dashboard"></i> <span> Dashboard</span> </a>
								
							</li>
							
							
							<li >
								<a href="<?= base_url('absensi')?>" class="active"><i class="la la-calendar"></i> <span> Absensi</span> </a>
								
							</li>
							
							<li >
								<a href="#" ><i class="la la-money"></i> <span> Gaji</span> </a>
								
							</li>
							<li >
								<a href="<?= base_url('schedule')?>" ><i class="la la-calendar"></i> <span>Jadwal</span> </a>
								
							</li>
							
							
						</ul>
						<?php }?>
					</div>
                </div>
            </div>