<?php

class Crud {


	/**
	 * host
	 * @var protected
	*/
	protected $host = 'localhost';

	/**
	 * user
	 * @var protected
	*/
	protected $user	= 'root';

	/**
	 * pass
	 * @var protected
	*/
	protected $pass	= '';

	/**
	 * db
	 * @var protected
	*/
	protected $db	= 'crud';

	/**
	 * mysqli
	 * @var public
	*/
	public $mysqli;

	/**
	 * id
	 * @var public
	*/
	public $id;

	/**
	 * username
	 * @var public
	*/
	public $username;

	/**
	 * first_name
	 * @var public
	*/
	public $first_name;

	/**
	 * last_name
	 * @var public
	*/
	public $last_name;

	/**
	 * created_on
	 * @var public
	*/
	public $created_on;

	/**
	 * status
	 * @var public
	*/
	public $status;

	/**
	 * errors to hold validation errors
	 * @var public
	*/
	public $errors = array();

	/**
	 * data to pass back data
	 * @var public
	*/
	public $data = array();

	/**
	 * application/json
	 * @var public
	*/
	public $content_type = "application/json";



	/**
	 * Function connect()
	*/
	public function connect() {

		$this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);

		if ($this->mysqli->connect_errno) {
			echo 'Failed to connect: ' . $this->mysqli->connect_error;
		}

		// Return $mysqli;
		return $this->mysqli;
	}



	// Constructor
	public function __construct() {
		$this->connect();
		$this->inputs();
	}


	/**
	 * return $_GET['action']
	 */
	private function get_action_param() {
        return $_GET['action'];
    }


    /**
     * return class method
     */
    public function inputs() {
    	switch ($this->get_action_param()) {
    		case 'add':
				$this->create();
				break;

			case 'edit':
				$this->edit();
				break;
			
			case 'delete':
				$this->delete();
				break;
    	}
    }


    /**
	 * @return json_encode
	 */
	private function json($data) {
        if (is_array($data)) {
            return json_encode($data);
        }
    }


    /**
	 * @return response
	 */
    private function response($data) {
    	$this->set_headers();
    	echo $data;
    	exit();
    }


    /**
	 * @return application/json
	 */
    private function set_headers() {
    	header("Content-Type:" . $this->content_type);
    }


	/**
	 * Function Read()
	*/
	public function read() {

		$query = "SELECT * FROM users ORDER BY id ASC";
		$stmt = $this->mysqli->prepare($query) or $this->mysqli->connect_error;
		$stmt->execute();
		$stmt->bind_result($id, $username, $first_name, $last_name, $created_on, $status);


		// Contains data of SQL query
		$rows = array();

		// Fetching data
		while($stmt->fetch()) :
			$rows[] = [
				'id'		=> $id,
				'username'	=> $username,
				'f_name'	=> $first_name,
				'l_name'	=> $last_name,
				'created'	=> $created_on,
				'status'	=> ($status == 1) ? $status = 'Active' : $status = 'Inactive'
			];
		endwhile;

		// Return rows
		return $rows;
	}


	/**
	 * Function user_exist()
	 *
	 * @return boolean
	*/
	private function user_exist() {

		if (!empty($_POST['username'])) {
			$this->username = htmlspecialchars(strip_tags($_POST['username']));
		} else {
			$this->username = '';
		}

		$username = $this->mysqli->prepare("SELECT username FROM users WHERE username = ?");
		$username->bind_param('s', $this->username);
		$username->execute();
		$username->store_result();

        ($username->num_rows > 0) ? $username_exists = true : $username_exists = false;

        return $username_exists;
	}

	/**
	 * Function create()
	 *
	 * @return JSON
	*/
	public function create() {

        if (!$this->validateOnCreate()) {
        	$this->data['success'] = false;
        	$this->data['errors'] = $this->getErrors();
        } else {

        	// store data from post
        	$this->username 	= htmlspecialchars(strip_tags($_POST['username']));
			$this->first_name 	= htmlspecialchars(strip_tags($_POST['first_name']));
			$this->last_name 	= htmlspecialchars(strip_tags($_POST['last_name']));
			$this->status 		= htmlspecialchars(strip_tags($_POST['status']));

        	// run sql
        	$sql = "INSERT INTO users (username, first_name, last_name, created_on, status) VALUES (?,?,?,?,?)";
    		$stmt = $this->mysqli->prepare($sql);
    		$stmt->bind_param('ssssi', $this->username, $this->first_name, $this->last_name, date('Y-m-d'), $this->status);
    		$stmt->execute();

    		// return true
    		$this->data['success'] = true;
    		$this->data['message'] = 'New user was successfully added!';
        }

 		// return json data
 		$this->response($this->json($this->data));
	}



	/**
	 * Function edit()
	 *
	 * @return JSON
	*/
	private function edit() {

		if (!$this->validateOnEdit()) {
        	$this->data['success'] 	= false;
        	$this->data['errors'] 	= $this->getErrors();
        }
		else {

			// store data from post
			$user_id 			= htmlspecialchars(strip_tags($_POST['edit_user_id']));
			$this->first_name 	= htmlspecialchars(strip_tags($_POST['first_name']));
			$this->last_name 	= htmlspecialchars(strip_tags($_POST['last_name']));
			$this->status 		= htmlspecialchars(strip_tags($_POST['status']));

        	// run sql
        	$sql = "UPDATE users SET first_name = ?, last_name = ?, created_on = ?, status = ? WHERE id = ?";
    		$stmt = $this->mysqli->prepare($sql);
    		$stmt->bind_param('sssii', $this->first_name, $this->last_name, date('Y-m-d'), $this->status, $user_id);
    		$stmt->execute();

    		// return true
    		$this->data['success'] = true;
    		$this->data['message'] = 'Username ' . $this->username . ' was successfully edited';
		}
		
		// return json data
 		$this->response($this->json($this->data));
	}

	/**
	 * Function delete()
	 *
	 * @return JSON
	*/
	private function delete() {	

		$user_id = $_GET['delete_id'];

		if (!is_numeric($user_id)) {
			$this->errors['userId'] = 'Please, check if user ID is correct!';
		} 
		
		if (!empty($this->errors)) {
			$this->data['success'] = false;
			$this->data['errors_title'] = 'Something gets wrong!';
			$this->data['errors'] = $this->getErrors();
		} else {

			// run query
			$query = "DELETE FROM users WHERE id = ?";
			$stmt = $this->mysqli->prepare($query);
			$stmt->bind_param('i', $user_id);
			$stmt->execute();

			$this->data['success'] = true;
			$this->data['success_title'] = 'Success!';
			$this->data['message'] = 'User with ID ' . $user_id . ' was successfully deleted!';
 		}

 		// return json data
 		$this->response($this->json($this->data));
	}

	

	/**
	 * Function validateOnCreate()
	 *
	 * @return boolean
	*/
	private function validateOnCreate() {

		if (empty($_POST['username'])) {
			$this->errors['username'] 	= 'Username is requried!';
		}

		if (empty($_POST['first_name'])) {
			$this->errors['first_name'] = 'First name is requried!';
		}

		if (empty($_POST['last_name'])) {
			$this->errors['last_name'] 	= 'Last name is requried!';
		}

		if (empty($_POST['status'])) {
			$this->errors['status'] 	= 'Status is requried';
		}

        if ($this->user_exist()) {
            $this->errors['user_exist'] = 'Username already exist!';
        }

        return count($this->errors) ? 0 : 1;
	}

	/**
	 * Function validateOnEdit()
	 *
	 * @return boolean
	*/
	private function validateOnEdit() {

		if (empty($_POST['first_name'])) {
			$this->errors['first_name'] = 'First name is requried!';
		}

		if (empty($_POST['last_name'])) {
			$this->errors['last_name'] 	= 'Last name is requried!';
		}

		if (empty($_POST['status'])) {
			$this->errors['status'] 	= 'Status is requried';
		}

        if ($this->user_exist()) {
            $this->errors['user_exist'] = 'Username already exist!';
        }

        return count($this->errors) ? 0 : 1;
	}

	/**
	 * Function getErrors()
	 *
	 * @return array
	*/
	public function getErrors() {
		return $this->errors;
	} 

}


// Initialisation
$crud = new Crud;
