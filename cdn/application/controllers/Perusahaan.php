<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perusahaan extends CI_Controller
{
    public function upload_avatar()
    {
        $this->load->model('perusahaan_model');
        $data['current_user'] = $this->auth_model->current_user();

        if ($this->input->method() === 'post') {
            // the user id contain dot, so we must remove it
            $file_name = str_replace('.','',$data['current_user']->id);
            $config['upload_path']          = './assets/images/';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
            $config['file_name']            = $file_name;
            $config['overwrite']            = true;
            $config['max_size']             = 1024; // 1MB
            $config['max_width']            = 1080;
            $config['max_height']           = 1080;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('logo')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $uploaded_data = $this->upload->data();

                $new_data = [
                    'id' => $data['current_user']->id,
                    'logo' => $uploaded_data['file_name'],
                ];
        
                if ($this->perusahaan_model->update($new_data)) {
                    $this->session->set_flashdata('message', 'Avatar updated!');
                    redirect(site_url('admin/perusahaan/index'));
                }
            }
        }

        $this->load->view('admin/perusahaan/index.php', $data);
    }


    public function __construct()
    {
        parent::__construct();
        $this->load->model("perusahaan_model"); //load model perusahaan
    }

    //method pertama yang akan di eksekusi
    public function index()
    {
        //ambil fungsi getAll untuk menampilkan semua data perusahaan
        $data["data_perusahaan"] = $this->perusahaan_model->getAll();
        
        $this->load->view('admin/head',["menu"=>40]);
        $this->load->view('admin/perusahaan/index', $data);
        $this->load->view('admin/foot');
    }

    //method add digunakan untuk menampilkan form tambah data perusahaan
    public function add()
    {
        $data["data_perusahaani"] = $this->perusahaan_model->getAll();
        $Perusahaan = $this->perusahaan_model; //objek model
        $validation = $this->form_validation; //objek form validation
        $validation->set_rules($Perusahaan->rules()); //menerapkan rules validasi pada perusahaan_model
        //kondisi jika semua kolom telah divalidasi, maka akan menjalankan method save pada perusahaan_model
        if ($validation->run()) {
            $this->perusahaan_model->save();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data perusahaan berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("perusahaan/index");
        }
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/perusahaan/tambah', $data);
        $this->load->view('admin/foot');
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('perusahaan/index');

        $Perusahaan = $this->perusahaan_model;
        $validation = $this->form_validation;
        $validation->set_rules($Perusahaan->rules());

        if ($validation->run()) {
            $Perusahaan->update();
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  Data Perusahaan berhasil <strong>disimpan!</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>');
            redirect("perusahaan/index");
        }
        $data["data_perusahaan"] = $Perusahaan->getById($id);
        $this->load->view('admin/head',["menu"=>20]);
        $this->load->view('admin/perusahaan/edit', $data);
        $this->load->view('admin/foot');
    }

    public function delete()
    {
        $id = $this->input->get('id_perusahaan');
        if (!isset($id)) show_404();
        $this->perusahaan_model->delete($id);
        $msg['success'] = true;
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Data Perusahaan berhasil <strong>dihapus!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
        $this->output->set_output(json_encode($msg));
    }
}

