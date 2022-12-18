<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<!-- DataTables -->
				<a href="<?= base_url('karyawan/add'); ?>" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i>Tambah Agen</a>
				<h4 class="page-title">DATA KARYAWAN</h4>
					<div mb-5>
		                <!-- Menampilkan flashh data (pesan saat data berhasil disimpan)-->
		                <?php if ($this->session->flashdata('message')) :
		                    echo $this->session->flashdata('message');
		                endif; ?>
		            </div>
					<div class="card">
					<div class="card-body">

						<div class="table-responsive">
							<table class="table table-hover mt-3" id="tableData" width="100%" cellspacing="0">
								<thead>
									<tr align="center">
										<th>Kode karyawan</th>
										<th>Nama</th>
										<th>Nama Perusahaan</th>  
										<th>Jabatan</th>
										<th>No Hp</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>

									<?php foreach ($data_karyawan as $row) : ?>
	                                    <tr align="center">
	                                    	<td><?= $row->kode_karyawan ?></td>
	                                        <td><?= $row->nama_karyawan ?></td>
	                                        <td><?= $row->nama_perusahaan ?></td>
	                                        <td><?= $row->jabatan ?></td>
	                                        <td><?= $row->no_hp ?></td>
	                                        <td><?= $row->status ?></td>
	                                        <td>
	                                            <a href="<?= site_url('karyawan/edit/' . $row->id_karyawan) ?>" class="btn btn-small"><i class="fas fa-edit"></i> Edit</a>
	                                            <a href="javascript:void(0);" data="<?= $row->id_karyawan ?>" class="item-delete btn btn-small text-danger"><i class="fas fa-trash"></i> Hapus</a>
	                                    </tr>
	                                <?php endforeach; ?>

								</tbody>
							</table>
						</div>
					</div>
				</div>


			</div>
			<!-- /.container-fluid -->

			<!-- Sticky Footer -->

		</div>
		<!-- /.content-wrapper -->

	</div>
	<!-- /#wrapper -->

	<!-- Modal dialog hapus data-->
	<div class="modal fade" id="myModalDelete" tabindex="-1" aria-labelledby="myModalDeleteLabel" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="myModalDeleteLabel">Konfirmasi</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
	                Anda ingin menghapus data karyawan ini?
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
	                <button type="button" class="btn btn-danger" id="btdelete">Lanjutkan</button>
	            </div>
	        </div>
	    </div>
	</div>

	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
	<script>
    //menampilkan data ketabel dengan plugin datatables
    $('#tableData').DataTable({
    	"bPaginate": false,
	    "bLengthChange": false,
	    "bFilter": true,
	    "bInfo": false,
	    "bAutoWidth": false
    });

    //menampilkan modal dialog saat tombol hapus ditekan
    $('#tableData').on('click', '.item-delete', function() {
        //ambil data dari atribute data 
        var id = $(this).attr('data');
        $('#myModalDelete').modal('show');
        //ketika tombol lanjutkan ditekan, data id akan dikirim ke method delete 
        //pada controller karyawan
        $('#btdelete').unbind().click(function() {
            $.ajax({
                type: 'ajax',
                method: 'get',
                async: false,
                url: '<?php echo base_url() ?>karyawan/delete',
                data: {
                    id_karyawan: id
                },
                dataType: 'json',
                success: function(response) {
                    $('#myModalDelete').modal('hide');
                    location.reload();
                }
            });
        });
    });
</script>

</body>

</html>