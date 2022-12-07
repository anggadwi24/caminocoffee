<div class="row">
	<div class="col-md-12">
		<div class="welcome-box">
			<div class="welcome-img">
			<?php 
                            if( $row->photo != ''){
                                if(file_exists('upload/user/'.$row->photo) ){
                                    echo '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';

                                }else{
                                    echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                                }
                                
                            }else{
                                echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                            }
                        ?>
			</div>
			<div class="welcome-det">
				<h3>Selamat datang, <?= $row->name ?></h3>
				<p><?=fullyDate(date('Y-m-d'))?></p>
			</div>
			<div class="float-right ml-auto">
				<h1 id="hours"></h1>
			</div>
		</div>
	</div>
</div>
<div class="row mb-5">
	<div class="col-lg-12">
		<div class="card mb-0">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
					
						<!-- Calendar -->
						<div id="calendar"></div>
						<!-- /Calendar -->
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	
	<div class="col-lg-8 col-md-8">
		<section class="dash-section">
			<h1 class="dash-sec-title">Hari ini</h1>
			<div class="dash-sec-content">
				<div class="dash-info-list">
					<?php if($schedule->num_rows() > 0){?>
					<?php $sch = $schedule->row();?>
					<?php if($sch->status == 'on' AND $sch->shift_id != null){
						$shift=  $this->model_app->view_where('shift',array('id'=>$sch->shift_id))->row();
					?>
						<?php 
						if($absensiToday->num_rows() > 0){	
							$abs = $absensiToday->row();
						?>
						<a href="#" class="dash-card text-success">
							<div class="dash-card-container">
								<div class="dash-card-icon">
									<i class="fa fa-hourglass-o"></i>
								</div>
								<div class="dash-card-content">
									<p>Anda sudah melakukan absensi</p>
								</div>
							
							</div>
						</a>
						<?php }else{?>
						<a href="#" class="dash-card text-warning">
							<div class="dash-card-container">
								<div class="dash-card-icon">
									<i class="fa fa-hourglass-o"></i>
								</div>
								<div class="dash-card-content">
									<div class="d-flex justify-content-between">
										<p>Anda shift <?= $shift->name ?> </p>
										

									</div>
								</div>
								<div class="dash-card-avatars">
										<form action="<?= base_url('absensi/in') ?>" method="POST">
											<button class="btn btn-primary">ABSEN IN</button>
										</form>
								</div>
							
							</div>
						</a>
						<?php }?>
					<?php }else if($sch->status == 'off'){?>
						<a href="javascript:void(0)" class="dash-card text-danger">
							<div class="dash-card-container">
								<div class="dash-card-icon">
									<i class="fa fa-hourglass-o"></i>
								</div>
								<div class="dash-card-content">
									<p>Anda libur </p>
								</div>
								
							</div>
						</a>
					<?php }else {?>
						<?php $pengajuan = $this->db->query("SELECT * FROM pengajuan WHERE pegawai_id = '".$row->pegawai_id."' AND  '".date('Y-m-d')."' BETWEEN start AND end  AND approve ='y' "); ?>
						<?php if($pengajuan->num_rows() > 0) { $peng = $pengajuan->row()?>
							<a href="javascript:void(0)" class="dash-card text-danger">
							<div class="dash-card-container">
								<div class="dash-card-icon">
									<i class="fa fa-hourglass-o"></i>
								</div>
								<div class="dash-card-content">
									<p>Anda cuti / DC dengan perihal <?= $peng->perihal?></p>
								</div>
								
							</div>
							</a>
						<?php }else{?>
							<a href="javascript:void(0)" class="dash-card text-danger">
							<div class="dash-card-container">
								<div class="dash-card-icon">
									<i class="fa fa-hourglass-o"></i>
								</div>
								<div class="dash-card-content">
									<p>Anda CUTI / DC </p>
								</div>
								
							</div>
							</a>
						<?php }?>
					<?php }?>
					
					<?php }else{?>
						<a href="javascript:void(0)" class="dash-card text-danger">
						<div class="dash-card-container">
							<div class="dash-card-icon">
								<i class="fa fa-times"></i>
							</div>
							<div class="dash-card-content">
								<p>Anda tidak memiliki jadwal</p>
							</div>
							
						</div>
					</a>
					<?php }?>
				</div>
				<?php 
					if($scheduleEmployee->num_rows() > 0){
						foreach($scheduleEmployee->result() as $rows){
							echo '<div class="dash-info-list">
							<a href="#" class="dash-card">
								<div class="dash-card-container">
									<div class="dash-card-icon">
										<i class="fa fa-suitcase"></i>
									</div>
									<div class="dash-card-content">
										<p>'.$rows->name.' shift '.$rows->shift_name.'</p>
									</div>
									<div class="dash-card-avatars">
										<div class="e-avatar">
										';
										if( $rows->photo != ''){
											if(file_exists('upload/user/'.$rows->photo) ){
												echo '<img src="'.base_url('upload/user/'.$rows->photo).'" alt="'.$rows->name.'">';
			
											}else{
												echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$rows->name.'">';
											}
											
										}else{
											echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$rows->name.'">';
										}
										
									echo '</div>
									</div>
								</div>
							</a>
						</div>';
						}
					}
				?>
				

				

			</div>
		</section>

		<section class="dash-section">
			<h1 class="dash-sec-title">Besok</h1>
			<div class="dash-sec-content">
				<div class="dash-info-list">
					<div class="dash-card">
						<div class="dash-card-container">
							
						
				<?php 
					if($scheduleTommorow->num_rows() > 0){
						$scht = $scheduleTommorow->row();
						if($scht->status == 'on' AND $scht->shift_id != null){
							$shiftx = $this->model_app->view_where('shift',array('id'=>$scht->shift_id))->row();
							echo '<div class="dash-card-icon">
									<i class="fa fa-suitcase"></i>
								</div>
								<div class="dash-card-content">
									<p>Anda shift '.$shiftx->name.'</p>
								</div>
								';	
						}else{
							if($scht->status == 'off'){
								$text = 'Anda libur';
							}else{
								$text = 'Anda cuti/DC';
							}
							echo '<div class="dash-card-icon">
									<i class="fa fa-times"></i>
								</div>
								<div class="dash-card-content">
									<p>'.$text.'</p>
								</div>
								';	
						}

					}else{
						
						echo '<div class="dash-card-icon">
								<i class="fa fa-hourglass-o"></i>
							</div>
							<div class="dash-card-content">
								<p>Anda tidak memiliki jadwal</p>
							</div>
							';
					}
				?>
						</div>
					</div>
				</div>
				<?php 
					if($scheduleEmployeeTommorow->num_rows() > 0){
						foreach($scheduleEmployeeTommorow->result() as $rowx){
							echo '<div class="dash-info-list">
							<a href="#" class="dash-card">
								<div class="dash-card-container">
									<div class="dash-card-icon">
										<i class="fa fa-suitcase"></i>
									</div>
									<div class="dash-card-content">
										<p>'.$rowx->name.' shift '.$rowx->shift_name.'</p>
									</div>
									<div class="dash-card-avatars">
										<div class="e-avatar">
										';
										if( $rowx->photo != ''){
											if(file_exists('upload/user/'.$rowx->photo) ){
												echo '<img src="'.base_url('upload/user/'.$rowx->photo).'" alt="'.$rowx->name.'">';
			
											}else{
												echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$rowx->name.'">';
											}
											
										}else{
											echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$rowx->name.'">';
										}
										
									echo '</div>
									</div>
								</div>
							</a>
						</div>';
						}
					}
				?>
			</div>
			
		</section>

		
	</div>

	<div class="col-lg-4 col-md-4">
		<div class="dash-sidebar">
			<section>
				<h5 class="dash-title">Projects</h5>
				<div class="card">
					<div class="card-body">
						<div class="time-list">
							<div class="dash-stats-list">
								<h4>71</h4>
								<p>Total Tasks</p>
							</div>
							<div class="dash-stats-list">
								<h4>14</h4>
								<p>Pending Tasks</p>
							</div>
						</div>
						<div class="request-btn">
							<div class="dash-stats-list">
								<h4>2</h4>
								<p>Total Projects</p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section>
				<h5 class="dash-title">Your Leave</h5>
				<div class="card">
					<div class="card-body">
						<div class="time-list">
							<div class="dash-stats-list">
								<h4>4.5</h4>
								<p>Leave Taken</p>
							</div>
							<div class="dash-stats-list">
								<h4>12</h4>
								<p>Remaining</p>
							</div>
						</div>
						<div class="request-btn">
							<a class="btn btn-primary" href="#">Apply Leave</a>
						</div>
					</div>
				</div>
			</section>
			<section>
				<h5 class="dash-title">Your time off allowance</h5>
				<div class="card">
					<div class="card-body">
						<div class="time-list">
							<div class="dash-stats-list">
								<h4>5.0 Hours</h4>
								<p>Approved</p>
							</div>
							<div class="dash-stats-list">
								<h4>15 Hours</h4>
								<p>Remaining</p>
							</div>
						</div>
						<div class="request-btn">
							<a class="btn btn-primary" href="#">Apply Time Off</a>
						</div>
					</div>
				</div>
			</section>
			<section>
				<h5 class="dash-title">Upcoming Holiday</h5>
				<div class="card">
					<div class="card-body text-center">
						<h4 class="holiday-title mb-0">Mon 20 May 2019 - Ramzan</h4>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>