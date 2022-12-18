<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan_model extends CI_Model
{
    private $table = 'blw_karyawan';

    //validasi form, method ini akan mengembailkan data berupa rules validasi form       
    public function rules()
    {
        return [
            [
                'field' => 'kode_karyawan',  //samakan dengan atribute name pada tags input
                'label' => 'kode_karyawan',  // label yang kan ditampilkan pada pesan error
                'rules' => 'trim|required' //rules validasi
            ],

            [
                'field' => 'nama_karyawan',
                'label' => 'nama_karyawan',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'nama_perusahaan',
                'label' => 'nama_perusahaan',
                'rules' => 'trim|required'
            ],

            [
                'field' => 'jabatan',
                'label' => 'jabatan',
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
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            ]
        ];
    }

    //menampilkan data karyawan berdasarkan id karyawan
    public function getById($id)
    {
        return $this->db->get_where($this->table, ["id_karyawan" => $id])->row();
        //query diatas seperti halnya query pada mysql 
        //select * from karyawan where id_karyawan='$id'
    }

    //menampilkan semua data karyawan
    public function getAll()
    {
        $this->db->from($this->table);
        $this->db->order_by("id_karyawan", "desc");
        $query = $this->db->get();
        return $query->result();
        //fungsi diatas seperti halnya query 
        //select * from karyawan order by id_karyawan desc
    }

    //menyimpan data karyawan
    public function save()
    {
        $data = array(
            "kode_karyawan" => $this->input->post('kode_karyawan'),
            "nama_karyawan" => $this->input->post('nama_karyawan'),
            "nama_perusahaan" => $this->input->post('nama_perusahaan'),
            "jabatan" => $this->input->post('jabatan'),
            "no_hp" => $this->input->post('no_hp'),
            "email" => $this->input->post('email'),
            "status" => $this->input->post('status')
        );
        return $this->db->insert($this->table, $data);
    }

    //edit data karyawan
    public function update()
    {
        $data = array(
            "kode_karyawan" => $this->input->post('kode_karyawan'),
            "nama_karyawan" => $this->input->post('nama_karyawan'),
            "nama_perusahaan" => $this->input->post('nama_perusahaan'),
            "jabatan" => $this->input->post('jabatan'),
            "no_hp" => $this->input->post('no_hp'),
            "email" => $this->input->post('email'),
            "status" => $this->input->post('status')
        );
        return $this->db->update($this->table, $data, array('id_karyawan' => $this->input->post('id_karyawan')));
    }

    //hapus data karyawan
    public function delete($id)
    {
        return $this->db->delete($this->table, array("id_karyawan" => $id));
    }
}

