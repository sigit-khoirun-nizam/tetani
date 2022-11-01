<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<a href="<?php echo site_url('kemitraan') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
				<h4 class="page-title">Edit Kemitraan</h4>

				<div class="card mb-3">
					<div class="card-body">

						<?php
	                    //create form
	                    $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

	                    	<div class="form-group">
								<label for="kode_kemitraan">Kode Kemitraan</label>
								<input type="hidden" class="form-control" id="id_kemitraan" name="id_kemitraan" value=" <?= $data_kemitraan->id_kemitraan; ?>">
								<input class="form-control"	type="text" name="kode_kemitraan" id="kode_kemitraan" value=" <?= $data_kemitraan->kode_kemitraan; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('kode_kemitraan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_kemitraan">Nama</label>
								<input class="form-control"	type="text" name="nama_kemitraan" id="nama_kemitraan" value=" <?= $data_kemitraan->nama_kemitraan; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('nama_kemitraan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="kategori_produk">Kategori Produk</label>
									<!-- <input type="checkbox" name="Kategori_produk" value="Buah" />Buah <br>
									<input type="checkbox" name="Kategori_produk" value="Hasil Kebun" />Hasil Kebun <br>
									<input type="checkbox" name="Kategori_produk" value="Rempah" />Rempah <br>
									<input type="checkbox" name="Kategori_produk" value="Semuanya" />Semuanya <br> <br> -->
								<input class="form-control" type="text" name="kategori_produk" id="kategori_produk" value=" <?= $data_kemitraan->kategori_produk; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kategori_produk') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_hp">Nomor HP</label>
								<input class="form-control" type="text" name="no_hp" id="no_hp" value=" <?= $data_kemitraan->no_hp; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_hp') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="kota">Kota</label>
								<input class="form-control" type="text" name="kota" id="kota" value=" <?= $data_kemitraan->kota; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kota') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="provinsi">Provinsi</label>
								<input class="form-control" type="text" name="provinsi" id="provinsi" value=" <?= $data_kemitraan->provinsi; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('provinsi') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_rekening">Nama Rekening</label>
								<input class="form-control" type="text" name="nama_rekening" id="nama_rekening" value=" <?= $data_kemitraan->nama_rekening; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('nama_rekening') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_rekening">Nomor Rekening</label>
								<input class="form-control" type="text" name="no_rekening" id="no_rekening" value=" <?= $data_kemitraan->no_rekening; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_rekening') ?>
	                            </small>
							</div>

							
							<div class="form-group">
								<label for="status">Status</label>
									<select class="form-control col-md-6" name="status" id="status" value=" <?= $data_kemitraan->status; ?>" required >
										<option value="Aktif">Aktif</option>
										<option value="Tidak Aktif">Tidak Aktif</option>
									</select> <br>
								<small class="text-danger">
	                                <?php echo form_error('status') ?>
	                            </small>
							</div>

							

							<div class="card-body">
								<button type="submit" class="btn btn-primary"><i class="la la-check-circle"></i> Update</button>
							</div>
						</form>

					</div>

				</div>
				<!-- /.container-fluid -->

				<!-- Sticky Footer -->

			</div>
			<!-- /.content-wrapper -->

		</div>
		<!-- /#wrapper -->

</body>

</html>