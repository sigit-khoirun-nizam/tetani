<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Afiliasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("afiliasi_model"); //load model Afiliasi
    }

    //method pertama yang akan di eksekusi
    public function index()
    {
        //ambil fungsi getAll untuk menampilkan semua data Afiliasi
        $data["data_afiliasi"] = $this->afiliasi_model->getAll();
        
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/afiliasi/index', $data);
        $this->load->view('admin/foot');
    }

    //method add digunakan untuk menampilkan form tambah data Afiliasi
    public function add()
    {
        $data["data_afiliasi"] = $this->afiliasi_model->getAll();
        $Afiliasi = $this->afiliasi_model; //objek model
        $validation = $this->form_validation; //objek form validation
        $validation->set_rules($Afiliasi->rules()); //menerapkan rules validasi pada afiliasi_model
        //kondisi jika semua kolom telah divalidasi, maka akan menjalankan method save pada afiliasi_model
        if ($validation->run()) {
            $this->afiliasi_model->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Afiliasi berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("afiliasi/index");
        }
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/afiliasi/tambah', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('afiliasi/index');

        $Afiliasi = $this->afiliasi_model;
        $validation = $this->form_validation;
        $validation->set_rules($Afiliasi->rules());

        if ($validation->run()) {
            $Afiliasi->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Afiliasi berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("afiliasi/index");
        }
        $data["data_afiliasi"] = $Afiliasi->getById($id);
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/afiliasi/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id_afiliasi');
        if (!isset($id)) show_404();
        $this->afiliasi_model->delete($id);
        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Data Afiliasi berhasil <strong>dihapus!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
        $this->output->set_output(json_encode($msg));
    }
}

