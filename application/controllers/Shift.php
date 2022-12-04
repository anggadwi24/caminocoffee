<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends CI_Controller 
{
	public function __construct()
	{
        parent::__construct();
    	if($this->session->userdata('isLog')){
			$this->id = $this->session->userdata['isLog']['id'];
			$this->role = $this->session->userdata['isLog']['role'];
			if($this->role != 'hrd'){
				$this->session->set_flashdata('error','Anda tidak bisa mengakses halaman ini');
				redirect('/');
			}
		}else{
			redirect('auth');
		}	
	}
    public function index(){
        $data['title'] = 'SHIFT - '.title();
		$data['page'] = 'Shift';
        $data['right'] = ' <a data-toggle="modal" data-target="#add_shift" class="btn add-btn" ><i class="fa fa-plus"></i> Tambah</a>
							';
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Shift</li>';
		$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css'];
        $data['ajax'] = ['assets/ajax/shift/edit.js'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js'];
        $data['record'] = $this->model_app->view_order('shift','id','desc');
        $this->template->load('template','hrd/shift',$data);
    }
    public function store(){
        if($this->input->method() == 'post'){
			
         
            $this->form_validation->set_rules('shift','Shift','required');
            $this->form_validation->set_rules('schedule_in','Jam masuk','required');
            $this->form_validation->set_rules('schedule_out','Jam keluar','required');
        
        







            if($this->form_validation->run() == false){
                
               $this->session->set_flashdata('error',validation_errors());
               redirect('shift');
                
            }else{
                
                $name = $this->input->post('shift');
               
                $in = $this->input->post('schedule_in');
                $out = $this->input->post('schedule_out');
                $sch_in = date('H:i:s',strtotime($in));
                $sch_out = date('H:i:s',strtotime($out));


              
                $data = array('name'=>$name,'schedule_in'=>$sch_in,'schedule_out'=>$sch_out,
                            );
               
                $this->model_app->insert('shift',$data);
                $this->session->set_flashdata('success','Shift berhasil ditambah');
                redirect('shift');
            }
        
    
         }else{
            redirect('shift');
         }
    }

    public function edit(){
        if($this->input->method() == 'post'){
            $id = $this->input->post('id');
            $arr = null;
            $row = $this->model_app->view_where('shift',array('id'=>$id));
            if($row->num_rows() > 0){
                $row = $row->row();
                $status = 200;
                $msg = null;
                $arr = array('form'=>base_url('shift/update/'.$row->id),'in'=>date('H:i',strtotime($row->schedule_in)),'shift'=>$row->name,'out'=>date('H:i',strtotime($row->schedule_out)));
            }else{
                $status = 201;
                $msg = 'Shift tidak ditemukan';
            }
            echo json_encode(array('status'=>$status,'msg'=>$msg,'arr'=>$arr));
        }else{
            redirect('shift');
        }
    }
    public function update($id){
        if($this->input->method() == 'post'){
                $row = $this->model_app->view_where('shift',array('id'=>$id));
                if($row->num_rows() > 0){
                    $this->form_validation->set_rules('shift','Shift','required');
                    $this->form_validation->set_rules('schedule_in','Jam masuk','required');
                    $this->form_validation->set_rules('schedule_out','Jam keluar','required');
                
                







                    if($this->form_validation->run() == false){
                        
                    $this->session->set_flashdata('error',validation_errors());
                    redirect('shift');
                        
                    }else{
                        
                        $name = $this->input->post('shift');
                    
                        $in = $this->input->post('schedule_in');
                        $out = $this->input->post('schedule_out');
                        $sch_in = date('H:i:s',strtotime($in));
                        $sch_out = date('H:i:s',strtotime($out));


                    
                        $data = array('name'=>$name,'schedule_in'=>$sch_in,'schedule_out'=>$sch_out,
                                    );
                    
                        $this->model_app->update('shift',$data,array('id'=>$id));
                        $this->session->set_flashdata('success','Shift berhasil diubah');
                        redirect('shift');
                    }
                }else{
                    $this->session->set_flashdata('error','Shift tidak ditemukan');
                    redirect('shift');
                }
                
            }else{
                $this->session->set_flashdata('error','Wrong method');
                redirect('shift');
            }
        }
    	function delete(){
            if($this->input->method() == 'post'){
                
                $id = $this->input->post('id');

                $cek = $this->model_app->view_where('shift',array('id'=>$id));
                if($cek->num_rows() > 0){
                    
                    $this->model_app->delete('shift',array('id'=>$id));
                    $this->session->set_flashdata('success','Shift berhasil dihapus');
                    redirect('shift');
                }else{
                    $this->session->set_flashdata('error','Shift tidak ditemukan');
                    redirect('shift');
                }
                
            }else{
                $this->session->set_flashdata('error',json_encode('Wrong Method'));
                redirect('shift');
            }
        }


}