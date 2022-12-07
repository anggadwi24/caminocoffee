<?php 
/*
-- ---------------------------------------------------------------
-- MARKETPLACE MULTI BUYER MULTI SELLER + SUPPORT RESELLER SYSTEM
-- CREATED BY : ROBBY PRIHANDAYA
-- COPYRIGHT  : Copyright (c) 2018 - 2019, PHPMU.COM. (https://phpmu.com/)
-- LICENSE    : http://opensource.org/licenses/MIT  MIT License
-- CREATED ON : 2019-03-26
-- UPDATED ON : 2019-03-27
-- ---------------------------------------------------------------
*/
class Model_app extends CI_model{
    public function view($table){
        return $this->db->get($table);
    }

    public function insert($table,$data){
        return $this->db->insert($table, $data);
    }
    public function insert_id($table,$data){
         $this->db->insert($table, $data);
         return $this->db->insert_id();
    }

    public function edit($table, $data){
        return $this->db->get_where($table, $data);
    }
 
    public function update($table, $data, $where){
        return $this->db->update($table, $data, $where); 
    }

    public function delete($table, $where){
        return $this->db->delete($table, $where);
    }

    public function view_where($table,$data){
        $this->db->where($data);
        return $this->db->get($table);
    }

    public function like($table,$data){
        $this->db->like($data);
        return $this->db->get($table);
    }

    public function like_custom($table,$data,$data2){
        $this->db->like($data);
        $this->db->or_like($data2);
        return $this->db->get($table);
    }

    public function view_ordering_limit($table,$order,$ordering,$baris,$dari){
        $this->db->select('*');
        $this->db->order_by($order,$ordering);
        $this->db->limit($dari, $baris);
        return $this->db->get($table);
    }

    public function view_where_ordering_limit($table,$data,$order,$ordering,$baris,$dari){
        $this->db->select('*');
        $this->db->where($data);
        $this->db->order_by($order,$ordering);
        $this->db->limit($dari, $baris);
        return $this->db->get($table);
    }
    
    public function view_ordering($table,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }   
    public function view_order($table,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->order_by($order,$ordering);
        return $this->db->get();
    }

    public function view_where_ordering($table,$data,$order,$ordering){
        $this->db->where($data);
        $this->db->order_by($order,$ordering);
        return $this->db->get($table);
        
    }
    public function view_where_ordering_group($table,$data,$order,$ordering,$group){
        $this->db->where($data);
        $this->db->group_by($group);
        $this->db->order_by($order,$ordering);
    
        return $this->db->get($table);
        
    }

    public function view_join_one($table1,$table2,$field,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }

    public function view_join_where($table1,$table2,$field,$where,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }
    public function join_where($table1,$table2,$field,$where){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
       
        return $this->db->get();
    }
    public function join_where2($table1,$table2,$field,$field1,$where){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field1);
        $this->db->where($where);
       
        return $this->db->get();
    }
    public function join_where2_select($select,$table1,$table2,$field,$field1,$where){
        $this->db->select($select);
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field1);
        $this->db->where($where);
       
        return $this->db->get();
    }
    public function getUserWhere($where){
        $table1= 'pegawai';
        $table2='users';
        $field = 'users_id';
        $field1 = 'id';
        $this->db->select('*,pegawai.id as pegawai_id');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field1);
        $this->db->where($where);
       
        return $this->db->get();
    }
    public function getAbsensi($employee,$month,$year){
        $this->db->select('absensi.id,pegawai.name,users.username,schedule_out,schedule_in,absensi.duration,absen_in,absen_out,absensi.date,early_out,early_in,pegawai.photo,shift.id as shift_id,schedule.id as schedule_id,pegawai.id as pegawai_id,users.id as users_id,shift.name as shift_name');
        $this->db->from('absensi');
        $this->db->join('pegawai', 'absensi.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');

        $this->db->join('schedule', 'absensi.schedule_id=schedule.id');
        $this->db->join('shift', 'schedule.shift_id=shift.id');
        $this->db->where('YEAR(date)',$year);
        $this->db->where('MONTH(date)',$month);


        if($employee){
            $this->db->where('users.username',$employee);
        }
        $this->db->order_by('absensi.id','desc');
        return $this->db->get();
    }
    public function view_pengajuan($employee,$month,$year){
        $this->db->select('*,pengajuan.created_at as tanggal,pengajuan.id as pengajuan_id');
        $this->db->from('pengajuan');
        $this->db->join('pegawai', 'pengajuan.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');
        if($employee != 'all'){
            $this->db->where('users.username',$employee);
        }
       
       
        $this->db->where('YEAR(end)',$year);

        $this->db->where('MONTH(start)',$month);
        $this->db->or_where('MONTH(end)',$month);





       
        $this->db->order_by('pengajuan.id','desc');
        return $this->db->get();
    }
    public function getPengajuanWhere($where){
        $this->db->select('*,pengajuan.created_at as tanggal,pengajuan.id as pengajuan_id');
        $this->db->from('pengajuan');
        $this->db->join('pegawai', 'pengajuan.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');
       
        $this->db->where($where);





       
        $this->db->order_by('pengajuan.id','desc');
        return $this->db->get();
    }
    public function getGajiPegawai($employee,$month,$year){
        $this->db->select('slip.*,pegawai.name,pegawai.photo,users.username,pegawai.id as pegawai_id,users.level,users.active');
        $this->db->from('slip');
        $this->db->join('pegawai', 'slip.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');
        $this->db->where('months',$month);
        $this->db->where('years',$year);
        


        if($employee != 'all' AND $employee){
            $this->db->where('users.username',$employee);
        }
        $this->db->order_by('slip.id','desc');
        return $this->db->get();
    }
    public function getGajiPegawaiWhere($id){
        $this->db->select('slip.*,pegawai.name,pegawai.photo,users.level,users.username,pegawai.id as pegawai_id');
        $this->db->from('slip');
        $this->db->join('pegawai', 'slip.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');
        $this->db->where('slip.id',$id);
     
        


        $this->db->order_by('slip.id','desc');
        return $this->db->get();
    }
    public function getAbsensiPegawai($employee,$month,$year){
        $this->db->select('absensi.id,pegawai.name,users.username,schedule_out,schedule_in,absen_in,absen_out,absensi.date,early_out,early_in,pegawai.photo,shift.id as shift_id,schedule.id as schedule_id,pegawai.id as pegawai_id,users.id as users_id,shift.name as shift_name');
        $this->db->from('absensi');
        $this->db->join('pegawai', 'absensi.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');

        $this->db->join('schedule', 'absensi.schedule_id=schedule.id');
        $this->db->join('shift', 'schedule.shift_id=shift.id');
        $this->db->where('YEAR(date)',$year);
        $this->db->where('MONTH(date)',$month);


        if($employee){
            $this->db->where('users.username',$employee);
        }
        $this->db->order_by('absensi.id','desc');
        return $this->db->get();
    }
    public function getAbsensiWhere($where){
        $this->db->select('absensi.id as absensi_id,pegawai.*,users.*,schedule_out,schedule_in,absen_in,absen_out,absensi.date,early_out,early_in,pegawai.photo,shift.id as shift_id,schedule.id as schedule_id,pegawai.id as pegawai_id,users.id as users_id,shift.name as shift_name');
        $this->db->from('absensi');
        $this->db->join('pegawai', 'absensi.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');

        $this->db->join('schedule', 'absensi.schedule_id=schedule.id');
        $this->db->join('shift', 'schedule.shift_id=shift.id');
        $this->db->where($where);
       
        $this->db->order_by('absensi.id','desc');
        return $this->db->get();
    }
    public function getScheduleWhere($where){
        $this->db->select('pegawai.*,users.*,schedule_out,schedule_in,pegawai.photo,shift.id as shift_id,schedule.id as schedule_id,pegawai.id as pegawai_id,users.id as users_id,shift.name as shift_name');
        $this->db->from('schedule');
        $this->db->join('pegawai', 'schedule.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');
        $this->db->join('shift', 'schedule.shift_id=shift.id');
        $this->db->where($where);
       
        $this->db->order_by('pegawai.name','asc');
        return $this->db->get();
    }
    public function getScheduleAll($where){
        $this->db->select('pegawai.*,users.*,pegawai.photo,schedule.id as schedule_id,pegawai.id as pegawai_id,users.id as users_id');
        $this->db->from('schedule');
        $this->db->join('pegawai', 'schedule.pegawai_id=pegawai.id');
        $this->db->join('users', 'pegawai.users_id=users.id');
     
        $this->db->where($where);
        $this->db->group_by('schedule.pegawai_id');
        $this->db->order_by('pegawai.name','asc');
        return $this->db->get();
    }
    public function getUser(){
        $table1= 'pegawai';
        $table2='users';
        $field = 'users_id';
        $field1 = 'id';
        $this->db->select('*,pegawai.id as pegawai_id');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field1);
        $this->db->order_by('name','asc');
  
       
        return $this->db->get();
    }
    public function getHRDWhere($where){
        $table1= 'hrd';
        $table2='users';
        $field = 'users_id';
        $field1 = 'id';
        $this->db->select('*,hrd.id as hrd_id');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field1);
        $this->db->where($where);
       
        return $this->db->get();
    }
    public function join_where_order($table1,$table2,$field,$where,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        return $this->db->get();
    }
    public function join_where_order2($table1,$table2,$field1,$field2,$where,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field1.'='.$table2.'.'.$field2);
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        return $this->db->get();
    }
    public function join_order($table1,$table2,$field,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
     
        $this->db->order_by($order,$ordering);
        return $this->db->get();
    }
    public function join_order2($table1,$table2,$field,$field2,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field2);
     
        $this->db->order_by($order,$ordering);
        return $this->db->get();
    }
    public function join_like_order2($table1,$table2,$field,$field2,$data,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field2);
        $this->db->like($data);
        $this->db->order_by($order,$ordering);
        return $this->db->get();
    }
    public function view_left_join_where($table1,$table2,$field,$where,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field,'left');
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }

    function view_seo($table,$field,$title)
    {
            
            $query = " SELECT * FROM $table WHERE  $field =  '$title' ";
            $query = $this->db->query($query);
            if($query->num_rows() > 0){
                return $title.'_'.rand(4,100);
            }else{
                return $title;
            }
        
    }
    
    function view_seo_updated($table,$field,$title,$fieldId,$id)
    {
            
            $query = " SELECT * FROM $table WHERE  $field =  '$title' AND $fieldId != $id ";
            $query = $this->db->query($query);
            if($query->num_rows() > 0){
                return $title.'_'.rand(4,100);
            }else{
                return $title;
            }
        
    }
    function generateInvoice(){
        $ci = & get_instance();
        $pre = date("ym",time());	
        $query = " SELECT * FROM cr_transaksi WHERE trans_no LIKE '$pre%' ORDER BY trans_no DESC LIMIT 1";
        $query = $ci->db->query($query);
        $rsv_no = "$pre"."0000";
        foreach($query->result() as $row){
            $rsv_no = $row->trans_no;
        }
        $rsv_no = intval($rsv_no) + 1;
        return  $rsv_no;
		
    }
 
}