<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<a href="<?php echo site_url('kemitraan') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
				<h4 class="page-title">Tambah Kemitraan</h4>

				<div class="card mb-3">
					<div class="card-body">

						<?php
	                    //create form
	                    $attributes = array('id_kemitraan' => 'FrmAddMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

	                    	<div class="form-group">
								<label for="kode_kemitraan">Kode Kemitraan</label>
								<input class="form-control"	type="text" name="kode_kemitraan" id="kode_kemitraan" value=" <?= set_value('kode_kemitraan'); ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('kode_kemitraan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_kemitraan">Nama</label>
								<input class="form-control"	type="text" name="nama_kemitraan" id="nama_kemitraan" value=" <?= set_value('nama_kemitraan'); ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('nama_kemitraan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="kategori_produk">Kategori Produk</label>
									<!-- <input type="checkbox" name="kategori_produk[]" value="Buah" />Buah <br>
									<input type="checkbox" name="kategori_produk[]" value="Hasil Kebun" />Hasil Kebun <br>
									<input type="checkbox" name="kategori_produk[]" value="Rempah" />Rempah <br>
									<input type="checkbox" name="kategori_produk[]" value="Semuanya" />Semuanya <br> -->

									<input class="form-control" type="text" name="kategori_produk" id="kategori_produk" value=" <?= set_value('kategori_produk'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kategori_produk') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_hp">Nomor HP</label>
								<input class="form-control" type="text" name="no_hp" id="no_hp" value=" <?= set_value('no_hp'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_hp') ?>
	                            </small>
							</div>

							

							<div class="form-group">
								<label for="kota">Kota</label>
								<input class="form-control" type="text" name="kota" id="kota" value=" <?= set_value('kota'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kota') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="provinsi">Provinsi</label>
								<input class="form-control" type="text" name="provinsi" id="provinsi" value=" <?= set_value('provinsi'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('provinsi') ?>
	                            </small>
							</div>


							<div class="form-group">
								<label for="nama_rekening">Nama Rekening</label>
								<input class="form-control" type="text" name="nama_rekening" id="nama_rekening" value=" <?= set_value('nama_rekening'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('nama_rekening') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_rekening">Nomor Rekening</label>
								<input class="form-control" type="text" name="no_rekening" id="no_rekening" value=" <?= set_value('no_rekening'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_rekening') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="status">Status</label>
									<select class="form-control col-md-6" name="status" id="status" value=" <?= set_value('status'); ?>" required >
										<option value="Aktif">Aktif</option>
										<option value="Tidak Aktif">Tidak Aktif</option>
									</select> <br>
								<small class="text-danger">
	                                <?php echo form_error('status') ?>
	                            </small>
							</div>

							<div class="card-body">
								<button type="submit" class="btn btn-primary"><i class="la la-check-circle"></i> Simpan</button>
								<button type="reset" class="btn btn-warning"><i class="la la-refresh"></i> Reset</button>
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