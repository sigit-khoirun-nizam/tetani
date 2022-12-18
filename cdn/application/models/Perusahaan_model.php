<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perusahaan_model extends CI_Model
{
    private $table = 'blw_perusahaan';

    //validasi form, method ini akan mengembailkan data berupa rules validasi form       
    public function rules()
    {
        return [
            [
                'field' => 'nama_perusahaan',  //samakan dengan atribute name pada tags input
                'label' => 'nama_perusahaan',  // label yang kan ditampilkan pada pesan error
                'rules' => 'trim|required' //rules validasi
            ],

            [
                'field' => 'brand_perusahaan',
                'label' => 'brand_perusahaan',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'no_hp',
                'label' => 'no_hp',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'alamat',
                'label' => 'alamat',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'kota',
                'label' => 'kota',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'provinsi',
                'label' => 'provinsi',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'nama_rekening',
                'label' => 'nama_rekening',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'no_rekening',
                'label' => 'no_rekening',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'website',
                'label' => 'website',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'npwp',
                'label' => 'npwp',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'logo',
                'label' => 'logo',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'code',
                'label' => 'code',
                'rules' => 'trim|required'
            ]
        ];
    }

    //menampilkan data perusahaan berdasarkan id perusahaan
    public function getById($id)
    {
        return $this->db->get_where($this->table, ["id_perusahaan" => $id])->row();
        //query diatas seperti halnya query pada mysql 
        //select * from perusahaan where id_perusahaan='$id'
    }

    //menampilkan semua data perusahaan
    public function getAll()
    {
        $this->db->from($this->table);
        $this->db->order_by("id_perusahaan", "desc");
        $query = $this->db->get();
        return $query->result();
        //fungsi diatas seperti halnya query 
        //select * from perusahaan order by id_perusahaan desc
    }

    //menyimpan data perusahaan
    public function save()
    {
        $data = array(
            "nama_perusahaan" => $this->input->post('nama_perusahaan'),
            "brand_perusahaan" => $this->input->post('brand_perusahaan'),
            "no_hp" => $this->input->post('no_hp'),
            "email" => $this->input->post('email'),
            "alamat" => $this->input->post('alamat'),
            "kota" => $this->input->post('kota'),
            "provinsi" => $this->input->post('provinsi'),
            "nama_rekening" => $this->input->post('nama_rekening'),
            "no_rekening" => $this->input->post('no_rekening'),
            "website" => $this->input->post('website'),
            "npwp" => $this->input->post('npwp'),
            "logo" => $this->input->post('logo'),
            "code" => $this->input->post('code')
        );
        return $this->db->insert($this->table, $data);
    }

    //edit data perusahaan
    public function update()
    {
        $data = array(
            "nama_perusahaan" => $this->input->post('nama_perusahaan'),
            "brand_perusahaan" => $this->input->post('brand_perusahaan'),
            "no_hp" => $this->input->post('no_hp'),
            "email" => $this->input->post('email'),
            "alamat" => $this->input->post('alamat'),
            "kota" => $this->input->post('kota'),
            "provinsi" => $this->input->post('provinsi'),
            "nama_rekening" => $this->input->post('nama_rekening'),
            "no_rekening" => $this->input->post('no_rekening'),
            "website" => $this->input->post('website'),
            "npwp" => $this->input->post('npwp'),
            "logo" => $this->input->post('logo'),
            "code" => $this->input->post('code')
        );
        return $this->db->update($this->table, $data, array('id_perusahaan' => $this->input->post('id_perusahaan')));
    }

    //hapus data perusahaan
    public function delete($id)
    {
        return $this->db->delete($this->table, array("id_perusahaan" => $id));
    }
}

