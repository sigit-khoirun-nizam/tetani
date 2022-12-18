<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("karyawan_model"); //load model karyawan
    }

    //method pertama yang akan di eksekusi
    public function index()
    {
        //ambil fungsi getAll untuk menampilkan semua data karyawan
        $data["data_karyawan"] = $this->karyawan_model->getAll();
        
        $this->load->view('admin/head',["menu"=>40]);
        $this->load->view('admin/karyawan/index', $data);
        $this->load->view('admin/foot');
    }

    //method add digunakan untuk menampilkan form tambah data karyawan
    public function add()
    {
        $data["data_karyawan"] = $this->karyawan_model->getAll();
        $Karyawan = $this->karyawan_model; //objek model
        $validation = $this->form_validation; //objek form validation
        $validation->set_rules($Karyawan->rules()); //menerapkan rules validasi pada karyawan_model
        //kondisi jika semua kolom telah divalidasi, maka akan menjalankan method save pada karyawan_model
        if ($validation->run()) {
            $this->karyawan_model->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Karyawan berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("karyawan/index");
        }
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/karyawan/tambah', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('karyawan/index');

        $Karyawan = $this->karyawan_model;
        $validation = $this->form_validation;
        $validation->set_rules($Karyawan->rules());

        if ($validation->run()) {
            $Karyawan->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Karyawan berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("karyawan/index");
        }
        $data["data_karyawan"] = $Karyawan->getById($id);
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/karyawan/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id_karyawan');
        if (!isset($id)) show_404();
        $this->karyawan_model->delete($id);
        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Data Karyawan berhasil <strong>dihapus!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
        $this->output->set_output(json_encode($msg));
    }
}

