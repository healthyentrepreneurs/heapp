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

    public function selectz($array_table_n, $table_n, $variable_1, $value_1, $limit = 0)
    {
        $this->db->select($array_table_n);
        $this->db->from($table_n);
        $this->db->where($variable_1, $value_1);
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
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
        // $this->db->db_debug = false;
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

    #New nSpe 23 2021
    public function select_byid_maxone($variable, $value, $table_name)
    {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($variable, $value);
        $this->db->order_by("id", "asc");
        $this->db->limit(1, 0);
        // $this->db->update($table_name, $updated_values);
        // $this->db->select($table_name);
        $query = $this->db->get()->result_array();
        return $query;
    }
    #New nSpe 23 2021
    public function select_bytwo_limit($variable, $value, $variable1, $value1, $variable2, $value2)
    {
        $this->db->select('*');
        $this->db->from('icon_table');
        $this->db->where($variable, $value);
        $this->db->where($variable1, $value1);
        $this->db->where($variable2, $value2);
        $this->db->order_by("id", "asc");
        $this->db->limit(1, 0);
        // $this->db->from($table_name);
        $query = $this->db->get()->result_array();
        return $query;
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
    public function join_suv_cohot($check_coh = 1, $chort_value = null)
    {
        $this->db->select('c.id as id,s.id as sid,s.name as name,s.type as type,s.surveydesc as surveydesc,s.image as image,s.image_url_small as image_url_small,c.cohort_id as cid,idnumber,cohort_name,cohort_id,name,surveydesc');
        $this->db->from('cohort_survey c');
        $this->db->join('survey s', 's.id=c.survey_id', 'left');
        if ($check_coh == 2) {
            $this->db->where('c.cohort_id', $chort_value);
        }
        $this->db->where('s.slug', 1);
        $query = $this->db->get()->result_array();
        return $query;
    }
    //Content Async

    //End Content Async
    public function join_suv_summery_nn($report_id, $from_from, $to_to)
    {
        $this->db->select('u.username,CONCAT(u.firstname," ",u.lastname) as fullname,c.id,s.id as surveyid,c.userid,s.name,s.surveydesc,c.dateadded dateaddedsurvey,s.image_url_small');
        $this->db->from('survey_report c');
        $this->db->join('survey s', 's.id=c.survey_id', 'left');
        $this->db->join('mdl_user u', 'u.id=c.userid', 'left');
        $this->db->where('s.slug', 1);
        $this->db->where('c.survey_id', $report_id);
        $this->db->where('DATE(c.dateadded) >=', date('Y-m-d', strtotime($from_from)));
        $this->db->where('DATE(c.dateadded) <=', date('Y-m-d', strtotime($to_to)));
        $this->db->order_by("c.dateadded", "desc");
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function join_suv_summery($report_id, $from_from, $to_to)
    {
        $this->db->select('u.username,u.fullname,u.id,u.surveyid,u.userid,u.name,u.surveydesc,u.dateadded dateaddedsurvey,u.image_url_small');
        $this->db->from('join_suv_report u');
        $this->db->where('u.slug', 1);
        $this->db->where('u.survey_id', $report_id);
        $this->db->where('DATE(u.dateadded) >=', date('Y-m-d', strtotime($from_from)));
        $this->db->where('DATE(u.dateadded) <=', date('Y-m-d', strtotime($to_to)));
        $this->db->order_by("u.dateadded", "desc");
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function join_suv_report($report_id, $from_from, $to_to)
    {
        $this->db->select('c.username,c.fullname,c.id,c.surveyid,c.userid,c.name,c.surveyobject,c.dateadded,c.surveyjson,c.surveydesc,c.dateadded dateaddedsurvey,c.image_url_small');
        $this->db->from('join_suv_report c');
        $this->db->where('c.slug', 1);
        $this->db->where('c.survey_id', $report_id);
        $this->db->where('DATE(c.dateadded) >=', date('Y-m-d', strtotime($from_from)));
        $this->db->where('DATE(c.dateadded) <=', date('Y-m-d', strtotime($to_to)));
        $this->db->order_by("c.dateadded", "desc");
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function join_suv_reportpapa($report_id, $from_from, $to_to)
    {
        $this->db->select('c.username,c.fullname,c.id,c.surveyid,c.userid,c.name,c.surveyobject,c.dateadded,c.surveyjson,c.surveydesc,c.dateadded dateaddedsurvey,c.image_url_small');
        $this->db->from('survey_report c');
        $this->db->where('c.slug', 1);
        $this->db->where('c.survey_id', $report_id);
        $this->db->where('DATE(c.dateadded) >=', date('Y-m-d', strtotime($from_from)));
        $this->db->where('DATE(c.dateadded) <=', date('Y-m-d', strtotime($to_to)));
        $this->db->order_by("c.dateadded", "desc");
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function join_suv_report_details($survey_id, $surveyrepo_id)
    {
        $this->db->select('c.id,s.id as surveyid,c.userid,c.surveyobject,s.name,s.surveydesc,s.surveyjson,c.dateadded dateaddedsurvey,s.image_url_small,c.imageifany');
        $this->db->from('mama_nam c');
        $this->db->join('survey s', 's.id=c.survey_id', 'left');
        $this->db->where('s.slug', 1);
        $this->db->where('s.id', $survey_id);
        $this->db->where('c.id', $surveyrepo_id);
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function books_reports_time($array_table_n, $from_from, $to_to)
    {
        $this->db->select($array_table_n);
        $this->db->from('viewtable');
        $this->db->where('DATE(viewtable.date_inserted) >=', date('Y-m-d', strtotime($from_from)));
        $this->db->where('DATE(viewtable.date_inserted) <=', date('Y-m-d', strtotime($to_to)));
        $this->db->group_by(array("viewtable.user_id", "viewtable.course_shortname", "viewtable.book_name", "viewtable.date_inserted"));
        $this->db->order_by("viewtable.user_id", "asc");
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function books_reports_chapter($array_table_n, $from_from, $to_to, $courseid, $bookid, $funco = "pop")
    {
        if ($funco == "pop") {
            $this->db->select($array_table_n);
        } else if ($funco == "book") {
            // name_course', 'book_name', 'user_id', 'he_names', 'date_inserted
            $this->db->select('name_course,book_name,user_id,he_names,date_inserted,DATE_FORMAT(date_inserted,"%m/%d/%Y") as datelike,DATE_FORMAT(date_inserted,"%H:%i") as hoursmins');
        }
        $this->db->from('viewtable');
        $this->db->where('DATE(viewtable.date_inserted) >=', date('Y-m-d', strtotime($from_from)));
        $this->db->where('DATE(viewtable.date_inserted) <=', date('Y-m-d', strtotime($to_to)));
        if ($courseid != "non") {
            $this->db->where('viewtable.course_id', $courseid);
        }
        if ($bookid != "non") {
            $this->db->where('viewtable.book_id', $bookid);
        }
        $this->db->group_by(array("viewtable.user_id", "viewtable.course_shortname", "viewtable.book_name", "viewtable.date_inserted"));
        $this->db->order_by("viewtable.user_id", "asc");
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function books_reports_chapterson($array_table_n, $from_from, $to_to, $courseid, $bookid, $funco = "pop", $chapterid = "non")
    {
        if ($funco == "pop") {
            $this->db->select($array_table_n);
        } else if ($funco == "book") {
            // name_course', 'book_name', 'user_id', 'he_names', 'date_inserted
            $this->db->select('name_course,book_name,chaptername,user_id,he_names,date_inserted,DATE_FORMAT(date_inserted,"%m/%d/%Y") as datelike,DATE_FORMAT(date_inserted,"%H:%i") as hoursmins');
        }
        $this->db->from('viewtable');
        $this->db->where('DATE(viewtable.date_inserted) >=', date('Y-m-d', strtotime($from_from)));
        $this->db->where('DATE(viewtable.date_inserted) <=', date('Y-m-d', strtotime($to_to)));
        if ($courseid != "non") {
            $this->db->where('viewtable.course_id', $courseid);
        }
        if ($bookid != "non") {
            $this->db->where('viewtable.book_id', $bookid);
        }
        if ($chapterid != "non") {
            $this->db->where('viewtable.view_id', $chapterid);
        }
        $this->db->group_by(array("viewtable.user_id", "viewtable.course_shortname", "viewtable.book_name", "viewtable.date_inserted"));
        $this->db->order_by("viewtable.user_id", "asc");
        $query = $this->db->get()->result_array();
        return $query;
    }
    public function book_select_uniqu_by($array_table_n, $array_order_by)
    {
        // https://dev.mysql.com/doc/refman/5.7/en/group-by-handling.html
        // https://www.percona.com/blog/2019/05/13/solve-query-failures-regarding-only_full_group_by-sql-mode/
        $this->db->select($array_table_n);
        $this->db->from('viewtable');
        // $this->db->where('DATE(viewtable.date_inserted) >=', date('Y-m-d', strtotime($from_from)));
        // $this->db->where('DATE(viewtable.date_inserted) <=', date('Y-m-d', strtotime($to_to)));
        $this->db->group_by($array_order_by);
        $this->db->order_by("viewtable.book_name", "asc");
        $query = $this->db->get()->result_array();
        return $query;
    }
    //Tem Fix Query
    public function book_query_two_model($array_table_n, $from_from, $to_to)
    {
        $this->db->select($array_table_n);
        $this->db->from('viewtable');
        $this->db->where('DATE(viewtable.date_inserted) >=', date('Y-m-d', strtotime($from_from)));
        $this->db->where('DATE(viewtable.date_inserted) <=', date('Y-m-d', strtotime($to_to)));
        $this->db->group_by(array("viewtable.user_id", "viewtable.course_shortname", "viewtable.book_name", "viewtable.date_inserted"));
        $this->db->order_by("viewtable.user_id", "asc");
        $query = $this->db->get()->result_array();
        // return $this->db->last_query();
        return $query;
    }
    public function get_value_max($user_id)
    {
        $this->db->from('viewtable');
        $this->db->select('date_inserted', 'user_id');
        $this->db->where('user_id', $user_id);
        $this->db->select_max('date_inserted');
        $query = $this->db->get()->result_array();
        return $query;
    }
    //Start Sync
    public function selectbooksviewuni($array_data, $value_1)
    {
        $this->db->select($array_data);
        $this->db->from('viewtable');
        $this->db->where('user_id', $value_1);
        // $this->db->order_by("token",'desc');
        $this->db->group_by("book_id");
        $query = $this->db->get()->result_array();
        return $query;
    }


    //TEMP FIX Missing Names Chapters
    public function selectmissingchapsuni($array_data)
    {
        $this->db->select($array_data);
        $this->db->from('viewtable');
        $this->db->where('view_id', '');
        // $this->db->order_by("token",'desc');
        $this->db->group_by("book_id");
        $query = $this->db->get()->result_array();
        return $query;
    }

    //TEMP Fix Missing BOOK Names
    public function selectmissingbooksuni($array_data)
    {
        $this->db->select($array_data);
        $this->db->from('viewtable');
        $this->db->where('book_name', '');
        // $this->db->order_by("token",'desc');
        $this->db->group_by("book_id");
        $query = $this->db->get()->result_array();
        return $query;
    }

    #Clean The Data 
    // https://www.mysqltutorial.org/mysql-delete-duplicate-rows/ Nice 
    public function getmedups()
    {
        // $this->db->select('*');
        // $this->db->from('icon_table');
        // $this->db->where('user_id', $value_1);
        // // $this->db->order_by("token",'desc');
        // $this->db->group_by("book_id");
        // $query = $this->db->get()->result_array();
        // return $query;
        $querym = "SELECT name,couseid,bookid, COUNT(bookid) FROM icon_table GROUP BY name,couseid,bookid HAVING COUNT(bookid)>1";
        $query = $this->db->query($querym)->result_array();
        // $query = $this->db->get()->result_array();
        return $query;
    }
    public function deletedubs()
    {
        $_query = "DELETE t1 FROM icon_table t1 INNER JOIN icon_table t2 WHERE t1.id>t2.id AND t1.name=t2.name AND t1.couseid=t2.couseid AND t1.bookid=t2.bookid";
        $query = $this->db->query($_query);
        return $query;
    }

    public function sleep_selectchapterbook($datestart, $dateend, $courseid, $userid)
    {
        $_query = "SELECT he_names,book_name,chaptername,book_id,view_id,user_id,course_shortname,course_id,date_inserted,COUNT(view_id) AS chaptercount,total.bookcount FROM viewtable
        ,(SELECT book_id as book_idin,COUNT(book_id) as bookcount from viewtable WHERE user_id='" . $userid . "' and course_id='" . $courseid . "' GROUP BY book_id) as total WHERE viewtable.book_id = total.book_idin and `user_id`='" . $userid . "' and DATE(viewtable.date_inserted)>='" . date('Y-m-d', strtotime($datestart)) . "' and DATE(viewtable.date_inserted)<='" . date('Y-m-d', strtotime($dateend)) . "' GROUP BY view_id";
        $detailsviewchapter = $this->db->query($_query)->result_array();
        return $detailsviewchapter;
        // echo $_query;
    }

    public function selectchapterbook($datestart, $dateend, $courseid, $bookid, $userid)
    {
        $_query = "SELECT id,he_names,book_name,chaptername,book_id,view_id,
        user_id,course_shortname,course_id,date_inserted,COUNT(view_id) AS chaptercount FROM viewtable WHERE viewtable.course_id = '" . $courseid . "'  and viewtable.book_id = '" . $bookid . "'  and
         `user_id`='" . $userid . "' and DATE(viewtable.date_inserted)>='" . date('Y-m-d', strtotime($datestart)) . "' 
        and DATE(viewtable.date_inserted)<='" . date('Y-m-d', strtotime($dateend)) . "' GROUP BY view_id ";
        $detailsviewchapter = $this->db->query($_query)->result_array();
        return $detailsviewchapter;
        // echo $_query;
    }

    public function selectbookviews($datestart, $dateend, $courseid, $userid)
    {
        $_query = "SELECT id,he_names,book_name,book_id,user_id,course_shortname,course_id,date_inserted,COUNT(book_id) AS bookcount 
        FROM viewtable WHERE user_id='" . $userid . "' and course_id='" . $courseid . "' 
        and DATE(viewtable.date_inserted)>='" . date('Y-m-d', strtotime($datestart)) . "' 
        and DATE(viewtable.date_inserted)<='" . date('Y-m-d', strtotime($dateend)) . "' 
        GROUP BY book_id ";
        return $this->db->query($_query)->result_array();
        // echo $_query;
    }
}
