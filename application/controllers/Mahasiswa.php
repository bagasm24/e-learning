<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Mahasiswa extends CI_Controller {

		public function __construct() {
			// Method ini akan berjalan di semua function (lebih praktis karena hanya perlu sekali memanggil saja)
			parent::__construct();
			apakah_login();
			$this->load->library('form_validation');
			$this->load->model('M_Auth');
			$this->load->model('M_Menu');
			$this->load->model('M_Mahasiswa');
			$this->load->model('M_Studi');
			$this->load->model('M_Jurusan');
			$this->load->model('M_Berkas');
			$this->load->model('M_Matkul');
			$this->load->model('M_Absensi');
		}

		public function index() {
			// Mengambil data dari sesi login user berdasarkan email
			$data = [
				'user' => $this->M_Auth->ceksesi(),
				'judul' => 'Siakad Mahasiswa'
			];
			$this->load->view('templates/session/header', $data);
			$this->load->view('templates/session/sidebar', $data);
			$this->load->view('templates/session/topbar', $data);
			$this->load->view('mahasiswa/index', $data); 
			$this->load->view('templates/session/footer');
		}

		public function studi() {
			$data = [
				'user' => $this->M_Auth->ceksesi(),
				'sema' => $this->M_Mahasiswa->sema(),
				'studima' => $this->M_Mahasiswa->studima(),
				'hasil' => $this->M_Studi->getAllStudi(),
				'judul' => 'Hasil Studi'
			];
			$this->load->view('templates/session/header', $data);
			$this->load->view('templates/session/sidebar', $data);
			$this->load->view('templates/session/topbar', $data);
			$this->load->view('mahasiswa/hasilstudi', $data);
			$this->load->view('templates/session/footer');
		}

		public function dokumen() {
			$data = [
				'user' => $this->M_Auth->ceksesi(),
				'sema' => $this->M_Mahasiswa->sema(),
				'studima' => $this->M_Mahasiswa->studima(),
				'dokumen' => $this->M_Berkas->getAllDokumen(),
				'tipe' => ['1', '2', '3'],
				'judul' => 'Materi dan Tugas'
			];
			$this->load->view('templates/session/header', $data);
			$this->load->view('templates/session/sidebar', $data);
			$this->load->view('templates/session/topbar', $data);
			$this->load->view('mahasiswa/dokumen', $data);
			$this->load->view('templates/session/footer');
		}

		public function uploadTugas() {
			$this->M_Berkas->uploadTugas();
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Laporan tugas berhasil diupload!</div>');
				redirect('mahasiswa/dokumen');
		}

		public function downloadDokumen($id) {
			$data = $this->db->get_where('dokumen',['id_doc'=>$id])->row();
			force_download('assets/dokumen/'.$data->berkas,NULL);
		}

		public function hapusDokumen($id) {
			$this->M_Berkas->delete($id);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Dokumen dihapus!</div>');
				redirect('mahasiswa/dokumen');
		}

		public function absensi() {
			$data = [
				'user' => $this->M_Auth->ceksesi(),
				'judul' => 'Absensi',
				'matkul' =>$this->M_Matkul->infoMatkul(),
				'absenhari' =>$this->M_Absensi->dailyAbsensiMahasiswa(),
				'sema' => $this->M_Mahasiswa->sema()
			];

			$this->form_validation->set_rules('npm', 'NPM', 'required|trim', ['required' => 'NPM tidak boleh kosong!']);
			
			$this->form_validation->set_rules('kode_matkul', 'Mata Kuliah', 'required|trim', [
				'required' => 'Matkul wajib diisi!'
			]);
			if ($this->form_validation->run() == false) {
				$this->load->view('templates/session/header', $data);
				$this->load->view('templates/session/sidebar', $data);
				$this->load->view('templates/session/topbar', $data);
				$this->load->view('mahasiswa/absensi', $data);
				$this->load->view('templates/session/footer');
			} else {
				$this->M_Absensi->create();
				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Absensi berhasil ditambahkan!</div>');
					redirect('mahasiswa/absensi');
			}
		}

		public function approveAbsensi() {
			$this->M_Absensi->approveAbsensi();
		}

		
		
	}