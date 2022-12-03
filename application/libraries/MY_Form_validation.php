<?php

/* Create MY_Form_validation.php  in ci_root/application/libraries */

class MY_Form_validation extends CI_Form_validation {

public function edit_unique($str, $field) {

    if (substr_count($field, '.') == 3) {
        list($table, $field, $id_field, $id_val) = explode('.', $field);
        $query = $this->CI->db->limit(1)->where($field, $str)->where($id_field . ' != ', $id_val)->get($table);
    } else {
        list($table, $field) = explode('.', $field);
        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
    }

    return $query->num_rows() === 0;
}

}
?>