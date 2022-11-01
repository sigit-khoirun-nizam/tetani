<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kemitraan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("kemitraan_model"); //load model kemitraan
    }

    //method pertama yang akan di eksekusi
    public function index()
    {
        //ambil fungsi getAll untuk menampilkan semua data kemitraan
        $data["data_kemitraan"] = $this->kemitraan_model->getAll();
        
        $this->load->view('admin/head',["menu"=>46]);
        $this->load->view('admin/kemitraan/index', $data);
        $this->load->view('admin/foot');
    }

    //method add digunakan untuk menampilkan form tambah data kemitraan
    public function add()
    {
        $data["data_kemitraan"] = $this->kemitraan_model->getAll();
        $Kemitraan = $this->kemitraan_model; //objek model
        $validation = $this->form_validation; //objek form validation
        $validation->set_rules($Kemitraan->rules()); //menerapkan rules validasi pada kemitraan_model
        //kondisi jika semua kolom telah divalidasi, maka akan menjalankan method save pada kemitraan_model
        if ($validation->run()) {
            $this->kemitraan_model->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Kemitraan berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("kemitraan/index");
        }
        $this->load->view('admin/head',["menu"=>46]);
        $this->load->view('admin/kemitraan/tambah', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('kemitraan/index');

        $Kemitraan = $this->kemitraan_model;
        $validation = $this->form_validation;
        $validation->set_rules($Kemitraan->rules());

        if ($validation->run()) {
            $Kemitraan->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Kemitraan berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("kemitraan/index");
        }
        $data["data_kemitraan"] = $Kemitraan->getById($id);
        $this->load->view('admin/head',["menu"=>46]);
        $this->load->view('admin/kemitraan/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id_kemitraan');
        if (!isset($id)) show_404();
        $this->kemitraan_model->delete($id);
        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Data Kemitraan berhasil <strong>dihapus!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
        $this->output->set_output(json_encode($msg));
    }
}

