<?php

class Universal_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function count_all_table($table_name)
    {
        return $this->db->count_all($table_name);
    }

    public function selectall($array_table_n, $table_n)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function selectzwherenot($array_table_n, $table_n, $leftout)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where('category!=', $leftout);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function selectz($array_table_n, $table_n, $variable_1, $value_1)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function selectz_var($array_table_n, $table_n, $variable_1, $value_1, $stringvariable)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $this->db->where($stringvariable);
        $query = $this->db->get()->result_array();
        return $query;
    }

    // newer
    public function selectzjoin_with_supervisors($array_table_n)
    {
        $this->db->select($array_table_n);
        $this->db->from('users a');
        $this->db->join('util_setting b', 'b.id=a.occupation', 'left');
        $this->db->where('a.category', 'employee');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function selectz_pigination($table_n, $variable_1, $value_1, $limit, $start)
    {
        $this->db->limit($limit, $start);
        // $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function fetch_tables_limit($limit, $start, $tablename)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get($tablename);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function selectzwherein($array_table_n, $table_n, $variable_1)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        // $this->db->like($variable_1);
        $this->db->where('STR_TO_DATE(date_done_task, "%Y-%m-%d") =', $variable_1);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function selectzy($array_table_n, $table_n, $variable_1, $value_1, $variable_2, $value_2)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $this->db->where($variable_2, $value_2);
        $query = $this->db->get()->result_array();
        // $sql = $this->db->last_query();
        return $query;
    }

    public function selectzy_var($array_table_n, $table_n, $variable_1, $value_1, $variable_2, $value_2, $stringvariable)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $this->db->where($variable_2, $value_2);
        $this->db->where($stringvariable);
        $query = $this->db->get()->result_array();
        // $sql = $this->db->last_query();
        return $query;
    }



    public function selectzunique($distinct, $table_n, $variable_1, $value_1)
    {
        $this->db->distinct($distinct);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function selectzx($array_table_n, $table_n, $variable_1, $value_1, $variable_2, $value_2, $variable_3, $value_3)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $this->db->where($variable_2, $value_2);
        $this->db->where($variable_3, $value_3);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function selectzxpp($array_table_n, $table_n, $variable_1, $value_1, $variable_2, $value_2, $variable_3, $value_3)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $this->db->where($variable_2, $value_2);
        $this->db->like($variable_3, $value_3);
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function selectzxppp($array_table_n, $table_n, $variable_1, $value_1, $variable_2, $value_2, $variable_3, $value_3, $variable_4, $value_4)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        $this->db->where($variable_2, $value_2);
        $this->db->where($variable_3, $value_3);
        $this->db->like($variable_4, $value_4);
        $query = $this->db->get()->result_array();
        return $query;
    }


    public function deletez($table_name, $variable_1, $value_1)
    {
        return $this->db->delete($table_name, array(
            $variable_1 => $value_1,
        ));
        // return $this->db->affected_rows();
    }

    public function deletezm($table_name, $variable_1, $value_1, $variable_2, $value_2)
    {
        $this->db->delete($table_name, array(
            $variable_1 => $value_1,
            $variable_2 => $value_2,
        ));
    }

    public function deletezn($table_name, $variable_1, $value_1, $variable_2, $value_2, $variable_3, $value_3)
    {
        $this->db->delete($table_name, array(
            $variable_1 => $value_1,
            $variable_2 => $value_2,
            $variable_3 => $value_3,
        ));
    }

    public function insertz($table_name, $array_value)
    {
        $this->db->insert($table_name, $array_value);
        return $this->db->insert_id();
    }

    public function insertzwhere($table_name, $array_value)
    {
        $this->db->insert($table_name, $array_value);
        return $this->db->insert_id();
    }

    public function updatez($variable, $value, $table_name, $updated_values)
    {
        $this->db->where($variable, $value);
        return $this->db->update($table_name, $updated_values);
    }

    public function updatem($variable, $value, $variable1, $value1, $table_name, $updated_values)
    {
        $this->db->where($variable, $value);
        $this->db->where($variable1, $value1);
        $this->db->update($table_name, $updated_values);
        return $this->db->affected_rows();
    }

    public function updatep($variable, $value, $variable1, $value1, $variable2, $value2, $table_name, $updated_values)
    {
        $this->db->where($variable, $value);
        $this->db->where($variable1, $value1);
        $this->db->where($variable2, $value2);
        $response = $this->db->update($table_name, $updated_values);
    }


    public function record_count($name_table)
    {
        return $this->db->count_all($name_table);
    }

    public function fetch_standard_table($limit, $start, $table_name)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get($table_name);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function replace($tablename, $arrayvalues)
    {
        $this->db->replace($tablename, $arrayvalues);
    }

    // public function updateOnDuplicate($table, $data)
    // {
    //     if (empty($table) || empty($data)) {
    //         return false;
    //     }

    //     $duplicate_data = array();
    //     foreach ($data as $key => $value) {
    //         $duplicate_data[] = sprintf("%s='%s'", $key, $value);
    //     }
    //     $sql = sprintf("%s ON DUPLICATE KEY UPDATE %s", $this->db->insert_string($table, $data), implode(',', $duplicate_data));
    //     $this->db->query($sql);
    //     return $this->db->insert_id();
    // }
    public function updateOnDuplicate($table, $data)
    {
        if (empty($table) || empty($data))
            return false;
        $duplicate_data = array();
        foreach ($data as $key => $value) {
            $duplicate_data[] = sprintf("%s='%s'", $key, mssql_escape($value));
        }
        $sql = sprintf("%s ON DUPLICATE KEY UPDATE %s", $this->db->insert_string($table, $data), implode(',', $duplicate_data));
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    public function selectallarray($array_table_n, $table_n)
    {
        // print_array($array_table_n);
        // $this->db->select($whatselect);
        $this->db->where($array_table_n);
        $this->db->from($table_n);
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function user_with_cat($id, $array_table_n)
    {
        $this->db->select($array_table_n);
        $this->db->from('user a');
        $this->db->join('category b', 'b.id=a.category', 'left');
        $this->db->where('a.id', $id);
        $query = $this->db->get()->result_array();
        return $query;
    }
}
