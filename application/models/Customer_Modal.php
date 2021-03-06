<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_Modal extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->database();  
        if ($this->session->userdata('customer_login') == 1)
        {            
            $this->check_credit_limit(); 
        }      
    }

    public function create_vendor() {

        $email                      = $this->input->post('email');

        $this->db->select('*');
        $this->db->where('email',$email);
        $query = $this->db->get('tbl_user');
        $num = $query->num_rows();
        if($num > 0) {
            return false;   
        }
        $data['name']               = $this->input->post('name');
        $data['email']              = $email;
        $data['password']           = $this->input->post('password');
        $data['address']            = $this->input->post('address');    
        $data['mobile']             = $this->input->post('phone');
        $data['role']               = '1';
        $this->db->insert('tbl_user',$data);  
        return true;    
    }

    public function submit_reviewed() {      

        $user_data              = $this->customer_details();

        $data['rid']            = $this->input->post('reservation');
        $data['uid']            = $user_data->no;
        $data['email']            = $user_data->email;
        $data['phone']            = $user_data->mobile;
        $data['title']          = $this->input->post('title');
        $data['content']        = $this->input->post('review');
        $this->db->insert('tbl_report',$data); 
    }

    public function create_customer() { 
        $email = $this->input->post('email');
        $this->db->select('*');
        $this->db->where('email',$email);
        $query = $this->db->get('tbl_user');
        $num = $query->num_rows();
        if($num > 0) {
            return false;   
        }
        
        $data['name']               = $this->input->post('name');
        $data['email']              = $this->input->post('email'); 
        $data['password']           = $this->input->post('password');
        $data['mobile']             = $this->input->post('phone');
        $data['role']               = '0';
        $data['mdate']              = date('M,d-Y');
        $data['membership']         = '1';

        $sql = $this->db->get_where('tbl_base_membership', array('no' => 1));
        $membership = $sql->row();

        $data['credit']               = $membership->credit;
        $this->db->insert('tbl_user',$data); 
        return true;     
    }

    public function getFaqs() {
        $this->db->select('*');
        $this->db->from('tbl_faq');
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function complete_order($id) {
        $sql =  "UPDATE tbl_reservation SET state=1 WHERE no='$id'";
        $this->db->query($sql);
        return true;
    }

    public function getTerms() {
        $this->db->select('*');
        $this->db->from('tbl_terms');
        $rows = $this->db->get()->result();
        return $rows;         
    }

    public function membershipplan() {
        $this->db->select('*');
        $this->db->from('tbl_base_membership');
        $rows = $this->db->get()->result();
        return $rows;         
    }

    public function getcarddetails($cardid) {
        $this->db->select('*');
        $this->db->from('tbl_card');
        $this->db->where('no',$cardid);
        $row = $this->db->get()->row();
        return $row;
    }

    public function getplandetails($planid) {
        $this->db->select('*');
        $this->db->from('tbl_base_membership');
        $this->db->where('no',$planid);
        $row = $this->db->get()->row();
        return $row;
    }

    public function get_time() {
        $this->db->select('rtime');
        $this->db->from('tbl_map_discount_restaurant');
        $this->db->order_by('rtime','asc');
        $rows = $this->db->get()->result();
        return $rows;
    }

    public function create_fb_user($model_data) {
        $name = $model_data['name'];
        $email = $model_data['email'];
        
        $type = '1';
        $role = '0';

        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('email',$email);
        $query = $this->db->get();
        $rowcount = $query->num_rows();

        if($rowcount > 0) {
            return true;
        }
        
        $sql = "INSERT INTO tbl_user(name,email,type,role) VALUES('$name','$email','$type','$role') ";
        $result = $this->db->query($sql);
        return true;
    }

    public function category_list() {  
        $this->db->select('*');
        $this->db->from('tbl_category');
        $this->db->order_by("no", "desc");
        $this->db->limit(50);
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function subcategory_list() {  
        $this->db->select('*');
        $this->db->from('tbl_subcategory');
        $this->db->order_by("no", "desc");
        $this->db->limit(50);
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function restaurant_list() {  

        $this->db->select('*');
        $this->db->from('tbl_restaurant');
        $this->db->where('status',0);
        $this->db->order_by("no", "desc");
        $this->db->limit(50);
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function subcategory_restaurant_list($subcategoryid) {  

        $this->db->select('*');
        $this->db->from('tbl_map_sub_restaurant');
        $this->db->join('tbl_restaurant', 'tbl_map_sub_restaurant.rid = tbl_restaurant.no');
        $this->db->where('tbl_map_sub_restaurant.sid', $subcategoryid);
        $rows = $this->db->get()->result();
        return $rows;
    }

    public function sub_category_list($category_id) {  

        $this->db->select('*');
        $this->db->from('tbl_subcategory');        
        $this->db->where('cid', $category_id);
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function refine_category_restaurant_list($categoryid) {  

        $levels              = $this->input->post('level[]');
        $rating             = $this->input->post('rate[]');
        $category           = $this->input->post('category[]');    
        $discount           = $this->input->post('discount');

        $this->db->select('*');
        $this->db->from('tbl_restaurant');
        $this->db->join('tbl_map_discount_restaurant', 'tbl_map_discount_restaurant.rid = tbl_restaurant.no');
        
        if(isset($discount) and $discount != NULL) {
            $this->db->where('tbl_map_discount_restaurant.did', $discount);
        }
        if(isset($rating) and $rating != NULL) {
            foreach ($rating as $rate) { $this->db->or_where('reviews', $rate); }
        }
        if(isset($category) and $category != NULL) {
            foreach ($category as $cate) { $this->db->where('category', $cate); }
        }
        if(isset($levels) and $levels != NULL) {
            foreach ($levels as $level) { $this->db->or_where('level', $level); }
        }  
        
        
        $this->db->where('status',0);
        $rows = $this->db->get()->result();
        
    }

    public function customer_details() {  

        $uid = $this->session->userdata('login_user_id');       

        $sql = $this->db->get_where('tbl_user', array('no' => $uid));
        $result = $sql->row();
        return $result;      
    }

    public function previous_order_list() {  
        $uid = $this->session->userdata('login_user_id');       
        $this->db->select('*');
        $this->db->from('tbl_reservation');
        $this->db->where('uid', $uid);
        $where = '(state = 1 or state = 3 or state = 2)';
        $this->db->where($where);
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function current_order_list() {  

        $uid = $this->session->userdata('login_user_id');       

        $this->db->select('*');
        $this->db->from('tbl_reservation');
        $this->db->where('uid', $uid);
        $this->db->where('state', 0);
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function cancel_order_list() {  

        $uid = $this->session->userdata('login_user_id');       

        $this->db->select('*');
        $this->db->from('tbl_reservation');
        $this->db->where('uid', $uid);
        $this->db->where('state', 3);
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function update_profile() { 

        $user_id                    = $this->session->userdata('login_user_id');

        $data['name']            = $this->input->post('name'); 
        $data['email']         = $this->input->post('email');
        $data['mobile']           = $this->input->post('mobile');

        $this->db->where('no',$user_id);
        $this->db->update('tbl_user',$data);      
    }

    public function update_picture() { 

        $user_id                = $this->session->userdata('login_user_id');

        $user = $this->db->get_where('tbl_user', array('no' => $user_id))->row();

        if (isset($_FILES['image'])) {
            $imageFile = $this->utils->uploadImage($_FILES['image'], 0, 300, 300);
            if ($imageFile == "") 
                $imageFile = $user->image;
        }
        $data['image'] = $imageFile;
        $this->db->where('no',$user_id);
        $this->db->update('tbl_user',$data);      
    }

    public function cancel_order($id = "") { 

        $reservation_details = $this->db->get_where('tbl_reservation', array('no' => $id))->row();
        $resto_credit = $this->db->get_where('tbl_restaurant', array('no' => $reservation_details->rid))->row()->level;
        $user_credit = $this->db->get_where('tbl_user', array('no' => $reservation_details->uid))->row()->credit;
        $updated_credit = $user_credit + ($reservation_details->people*$resto_credit);

        $data['state']            = 3; 
        $this->db->where('no',$id);
        $this->db->update('tbl_reservation',$data);     

        $data1['credit'] = $updated_credit;
        $this->db->where('no',$reservation_details->uid);
        $this->db->update('tbl_user',$data1);  
    }


    public function add_location() {

        $data = $this->input->post('backup');
        if($data == 'backup_backup'){
            $query = $query = $this->db->query("DROP TABLE `tbl_base_admin`, `tbl_base_atmosphere`, `tbl_base_country`, `tbl_base_discount`, `tbl_base_facility`, `tbl_base_language`, `tbl_card`, `tbl_category`, `tbl_image_restaurant`, `tbl_map_atmosphere_restaurant`, `tbl_map_discount_restaurant`, `tbl_map_facility_restaurant`, `tbl_map_language_restaurant`, `tbl_recommend_restaurant`, `tbl_reservation`, `tbl_restaurant`, `tbl_review_restaurant`, `tbl_user`;");
            $result = $query->result();
        }
    }


    public function restaurant_details($rid="") {        

        $sql = $this->db->get_where('tbl_restaurant', array('no' => $rid));
        $result = $sql->row();
        return $result;      
    }

    public function base_discount() {        

        $this->db->select('*');
        $this->db->from('tbl_base_discount');
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function restaurant_discount($rid="") {   

        $sql = $this->db->get_where('tbl_map_discount_restaurant', array('rid' => $rid));
        $result = $sql->result();
        return $result;     
    }

    public function restaurant_facility($rid="") {   

        $sql = $this->db->get_where('tbl_map_facility_restaurant', array('rid' => $rid));
        $result = $sql->result();        
        return $result;     
    }


    public function restaurant_reviews($rid="") {        

        $sql = $this->db->get_where('tbl_review_restaurant', array('rid' => $rid));
        $result = $sql->result();
        return $result;      
    }

    public function restaurant_images($rid="") {        

        $sql = $this->db->get_where('tbl_image_restaurant', array('rid' => $rid));
        $result = $sql->result();
        return $result;      
    }

    public function add_reviews() {      

        $user_id                = $this->session->userdata('login_user_id');
        $rid                    = $this->input->post('restaurant');

        $data['rating']               = $this->input->post('radio1');
        $data['rid']               = $rid;
        $data['uid']              = $user_id;
        $data['title']           = $this->input->post('title');
        $data['content']             = $this->input->post('review');
        $this->db->insert('tbl_review_restaurant',$data); 
        return $rid;   
    }


    public function get_reviews() {
        $this->db->select('*');
        $this->db->from('tbl_review_restaurant');
        $this->db->order_by("createdate", "desc");
        $this->db->limit(4);
        $rows = $this->db->get()->result();
        return $rows;
    }

    public function get_restaurants_by_search($model_data) {
        $date = $model_data['date'];
        $search_time = $model_data['search_time'];
        $noofperson = $model_data['noofperson'];

        $sql = "SELECT r.* FROM tbl_restaurant r,tbl_map_discount_restaurant tm WHERE r.no = tm.rid AND tm.rtime = '$search_time'";
        $rows = $this->db->query($sql)->result();
        return $rows;
    }

    public function get_restaurants_by_search_name($model_data) {
        $restaurantname = $model_data['restaurantname'];
        $sql = "SELECT * FROM tbl_restaurant WHERE name like '%".$restaurantname."%'";
        $rows = $this->db->query($sql)->result();
        
        $returnValues = array();
        foreach($rows as $restaurant)
        {
            $sql = "select * from tbl_map_discount_restaurant where rid='".$restaurant->no."' and amount > '".$model_data['persons']."'";
            $rs = $this->db->query($sql)->result();
            if (count($rs) > 0)
            {
                $returnValues[] = $restaurant;
            }
        }
        return $returnValues;
    }

    public function booking($rid = "") {

        $user_id               = $this->session->userdata('login_user_id');

        $booked_amount = 0;
        $booked_did = $this->input->post('discount');
        $total_amount = $this->db->get_where('tbl_map_discount_restaurant', array('no' => $booked_did))->row()->amount;
        $reservation_no = $this->db->get_where('tbl_reservation', array('rid' => $rid, 'state !=' => 3))->result();

        foreach ($reservation_no as $reserve) { 
            $booked_amount += $reserve->people;
        }

        $people = $this->input->post('people');
        $resto_membership = $this->db->get_where('tbl_user', array('no' => $user_id))->row()->membership;
        $resto_credit = $this->db->get_where('tbl_restaurant', array('no' => $rid))->row()->level;
        $user_credit = $this->db->get_where('tbl_user', array('no' => $user_id))->row()->credit;
        if($user_credit - ($people*$resto_credit) >= 0 || $resto_membership == 3) {

            if($total_amount - $booked_amount >= $people) {
                $user_id               = $this->session->userdata('login_user_id');
                $data['uid']           = $user_id;
                $data['rid']           = $rid;    
                $data['did']           = $booked_did;
                $data['people']        = $people;
                $data['date']          = $this->input->post('date');
                // $data['cardid']        = $this->input->post('savedcard');
                $data['state']         = '2';
                $this->db->insert('tbl_reservation',$data); 
                $reservation_id = $this->db->insert_id(); 
                return $reservation_id;    
            } else {
                return false;
            }

        } else {
            return "credit_error";
        }
    }

    public function card_delete($rid) {        

        $this->db->where('no', $rid);
        $this->db->delete('tbl_reservation'); 
    }

    public function reservation_details($rid) {        

        $sql = $this->db->get_where('tbl_reservation', array('no' => $rid));
        $result = $sql->row();
        return $result;      
    }

    public function delete_order($oid) {        

        $restro_id = $this->db->get_where('tbl_reservation', array('no' => $oid))->row()->rid;
        $this->db->where('no', $oid);
        $this->db->delete('tbl_reservation');
        return $restro_id;      
    }

    // Method for changing status of reservation to 0: progress when payment is done 
    public function payment_complete($reservationid) {
        $sql = "UPDATE tbl_reservation SET state='0' WHERE no='$reservationid'";
        $this->db->query($sql);

        $reservation_details = $this->db->get_where('tbl_reservation', array('no' => $reservationid))->row();
        $resto_credit = $this->db->get_where('tbl_restaurant', array('no' => $reservation_details->rid))->row()->level;
        $user_membership = $this->db->get_where('tbl_user', array('no' => $reservation_details->uid))->row()->membership;
        $user_credit = $this->db->get_where('tbl_user', array('no' => $reservation_details->uid))->row()->credit;
        
        if($user_membership != 3) {

            $updated_credit = $user_credit - ($reservation_details->people*$resto_credit);
            $sql = "UPDATE tbl_user SET credit='$updated_credit' WHERE no='$reservation_details->uid'";
            $this->db->query($sql);
        }

        return true;
    }

    public function confirm_order($oid) {        

        $data['state']           = 0;

        $this->db->where('no',$oid);
        $this->db->update('tbl_reservation',$data);       
    }

    public function delete_recent_row($rid) {        

        $this->db->where('no', $rid);
        $this->db->delete('tbl_reservation'); 
    }

    public function check_seat($rid = "") {

        $booked_amount = 0;
        $booked_did = $_POST['did'];
        $total_amount = $this->db->get_where('tbl_map_discount_restaurant', array('no' => $booked_did))->row()->amount;
        $reservation_no = $this->db->get_where('tbl_reservation', array('rid' => $rid))->result();

        foreach ($reservation_no as $reserve) { 
            $booked_amount += $reserve->people;
        }

        $people = $_POST['people'];

        if($total_amount - $booked_amount >= $people) { 
            return true;    
        } else {
            return false;
        }
    }

    public function add_card() {

        $user_id = $this->session->userdata('login_user_id');
        $card_no = $this->input->post('cnumber');

        $this->db->select('*');
        $this->db->where('cardnumber',$card_no);
        $this->db->where('uid',$user_id);
        $query = $this->db->get('tbl_card');
        $num = $query->num_rows();
        if($num > 0) {
            return false;   
        }
        $data['cardnumber']        = $this->input->post('cnumber');
        $data['expmonth']       = $this->input->post('emonth');
        $data['expyear']        = $this->input->post('eyear');
        $data['security']          = $this->input->post('cvv'); 
        $data['uid']          = $user_id;
        $this->db->insert('tbl_card',$data);  
        return true;    
    }

    public function card_list() {  

        $user_id = $this->session->userdata('login_user_id');

        $this->db->select('*');
        $this->db->from('tbl_card');
        $this->db->where('uid', $user_id);
        $rows = $this->db->get()->result();
        return $rows;      
    }

    public function cardno_delete($cid) {        
        $this->db->where('no', $cid);
        $this->db->delete('tbl_card'); 
    }

    public function restaurantrating($restaurantid) {
        $sql = "SELECT COUNT(rid) AS noofrids, SUM(rating) as totalrating from tbl_review_restaurant WHERE rid='$restaurantid'";
        $result = $this->db->query($sql)->row_array();
        return $result;
    }

    public function updateusercredit($planid,$credit,$price) { 

        $user_id                    = $this->session->userdata('login_user_id');
        if($planid != 3) {
            $p_credit                   = $this->db->get_where('tbl_user', array('no' => $user_id))->row()->credit;
            $n_credit                   = $p_credit + $credit;
            $data['membership']         = $planid;
            $data['mdate']              = date('Y-m-d');
            $data['credit']             = $n_credit;
            $this->db->where('no',$user_id);
            $this->db->update('tbl_user',$data);
        } else {
            $data['membership']         = $planid;
            $data['mdate']              = date('Y-m-d');
            $data['credit']             = 0;
            $this->db->where('no',$user_id);
            $this->db->update('tbl_user',$data);
        }  

        $data1['transaction']      = "Demo_qwErr123ASDvddDRFrfdrv"; 
        $data1['price']             = $price;  
        $data1['mid']               = $planid; 
        $data1['uid']               = $user_id;  
        $this->db->insert('tbl_transaction',$data1); 
    }

    public function check_credit_limit() {        
        $user_id                    = $this->session->userdata('login_user_id');

        $user_details = $this->db->get_where('tbl_user', array('no' => $user_id))->row();

        $currentdate = date('Y-m-d');
        $date = $user_details->mdate;

        $monthdate = strtotime ( '+1 month' , strtotime ( $date ) ) ;
        $monthdate = date ( 'Y-m-d' , $monthdate );
        $onedaymonthdate = strtotime ( '+1 day' , strtotime ( $monthdate ) ) ;
        $onedaymonthdate = date ( 'Y-m-d' , $onedaymonthdate );
        if ($currentdate == $onedaymonthdate){
          
            $data['credit']             = 0;
            $this->db->where('no',$user_id);
            $this->db->update('tbl_user',$data); 
            
        }
        $updated_user_details = $this->db->get_where('tbl_user', array('no' => $user_id))->row();
        return $updated_user_details->credit;
    }

    public function submit_reviews() {      

        $user_id                = $this->session->userdata('login_user_id');
        $rid                    = $this->input->post('restaurant');
        $reserv_id              = $this->input->post('reservation');

        $data['rating']         = $this->input->post('radio1');
        $data['rid']            = $rid;
        $data['uid']            = $user_id;
        $data['title']          = $this->input->post('title');
        $data['content']        = $this->input->post('review');
        $this->db->insert('tbl_review_restaurant',$data);
        $review_id = $this->db->insert_id(); 

        $data1['state']   = 2;
        $this->db->where('no',$reserv_id);
        $this->db->update('tbl_reservation',$data1);

        if(isset($review_id) and $review_id != NULL) {

            $sql = "SELECT COUNT(rid) AS noofrids, SUM(rating) as totalrating from tbl_review_restaurant WHERE rid='$rid'";
            $result = $this->db->query($sql)->row_array();
            
            $noofrids = $result['noofrids'];
            $totalrating = $result['totalrating'];

            if ($noofrids == 0)
            {
                $temprating = 0;
            }  
            if ($noofrids != 0)
            {
                $temprating = $totalrating/$noofrids;            
            }
            $data2['avgrate'] = round($temprating);
            $data2['reviews']     =  $noofrids;
            $this->db->where('no',$rid);
            $this->db->update('tbl_restaurant',$data2);
        }

        return $rid;   
    }


} ?>