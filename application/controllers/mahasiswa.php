<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends CI_Controller {

	public function list()
	{
		if($this->session->userdata('role')=='mahasiswa'){
			$id = $this->session->userdata('user_id');
			$where = array('npm' => $id);
			$arr_gambar = $this->model_user->photo_mahasiswa($id)->result();
			$pendaftar = $this->model_pendaftar->view_myproject()->result();
			$data['project'] = $this->model_project->view_data()->result();
			$data['pendaftar'] = array();
			$data['gambar'] = $arr_gambar[0];
			$data['mahasiswa'] = $this->model_user->cek_mahasiswa($id);
			foreach ($pendaftar as $pdr) :
				array_push($data['pendaftar'],$pdr->id_project);
			endforeach;

			$this->load->view('header',$data);
			$this->load->view('mahasiswa/project_list', $data);
			$this->load->view('footer');
		}else {
			redirect('auth/login');
		}
		
	}
	public function dashboard(){
		if($this->session->userdata('role')=='mahasiswa'){
			$id = $this->session->userdata('user_id');
			$data['pendaftar'] = $this->model_pendaftar->view_myproject()->result();
			$arr_gambar = $this->model_user->photo_mahasiswa($id)->result();
			$data['gambar'] = $arr_gambar[0];

			$this->load->view('header',$data);
			$this->load->view('mahasiswa/dashboard_mahasiswa', $data);
			$this->load->view('footer');
		}else {
			redirect('auth/login');
		}
	}
	public function daftar($id){
		$data = array(
			'id_pendaftar'	=>	$this->session->userdata('user_id') ,
			'id_project'	=>	$id ,
			'status_pendaftar'	=> "Menunggu Konfirmasi",
		);
		$this->model_pendaftar->input_pendaftar($data, 'tb_pendaftar');
		redirect('mahasiswa/list');
	}

	public function test(){
		$id = $this->session->userdata('user_id');
		$data['pendaftar'] = $this->model_pendaftar->view_myproject()->result();
		$data2['pendaftar'] = $this->model_pendaftar->pendaftar_project("14")->result();
		$data4['pendaftar'] = $this->model_user->cek_mahasiswa($id);

		$this->load->view('test', $data4);

	}
	public function profil(){
		$id = $this->session->userdata('user_id');
		$arr_gambar = $this->model_user->photo_mahasiswa($id)->result();
		$data['gambar'] = $arr_gambar[0];
		$data['mahasiswa'] = $this->model_user->cek_mahasiswa($id);

		$this->load->view('header',$data);
		$this->load->view('mahasiswa/profil_mahasiswa',$data);
		$this->load->view('footer');
	}

	public function update_profile(){
		$id = $this->input->post('npm');
		$phone_number = $this->input->post('phone');
		$address = $this->input->post('address');
		$skill = $this->input->post('skill');
		$interest = $this->input->post('interest');
		$profile_description = $this->input->post('profile_description');
		$picture = $_FILES['photo'];

		if($picture=''){}else{
			$config['upload_path']	= './assets/adminLTE/dist/img';
			$config['allowed_types'] = 'jpg|png|gif';
		}

		$this->load->library('upload', $config);
		if(!$this->upload->do_upload('photo')){
			echo "Upload Gagal!"; die();
		}else{
			$picture = $this->upload->data('file_name');
		}

		$data = array(
			'phone_number' => $phone_number,
			'address' => $address,
			'skill' => $skill,
			'interest' => $interest,
			'profile_description' => $profile_description,
			'photo' => $picture,
		);
	
		$where = array(
			'npm' => $id
		);
	
		$this->model_user->update_mahasiswa($where, $data);
		redirect('mahasiswa/profil');
	}
	
}
