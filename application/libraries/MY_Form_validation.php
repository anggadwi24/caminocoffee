<?php

/* Create MY_Form_validation.php  in ci_root/application/libraries */

class MY_Form_validation extends CI_Form_validation {

public function edit_unique($value, $params) {
    $CI =& get_instance(); $CI->load->database();
    $CI->form_validation->set_message('edit_unique', "%s telah digunakan");

    list($table, $field, $current_id) = explode(".", $params);
    
    $query = $CI->db->select()->from($table)->where($field, $value)->limit(1)->get();
    
    if ($query->row() && $query->row()->id != $current_id)
    {
        return FALSE;
    } else {
        return TRUE;
    }
}

}
?>