<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<a href="<?php echo site_url('petani') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
				<h4 class="page-title">Tambah Petani</h4>

				<div class="card mb-3">
					<div class="card-body">

						<?php
	                    //create form
	                    $attributes = array('id_petani' => 'FrmAddMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

	                    	<div class="form-group">
								<label for="kode_petani">Kode Petani</label>
								<input class="form-control"	type="text" name="kode_petani" id="kode_petani" value=" <?= set_value('kode_petani'); ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('kode_petani') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_petani">Nama</label>
								<input class="form-control"	type="text" name="nama_petani" id="nama_petani" value=" <?= set_value('nama_petani'); ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('nama_petani') ?>
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
								<label for="email">Email</label>
								<input class="form-control" type="text" name="email" id="email" value=" <?= set_value('email'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('email') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="alamat">Alamat</label>
								<input class="form-control" type="text" name="alamat" id="alamat" value=" <?= set_value('alamat'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('alamat') ?>
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
								<label for="status">Status</label>
									<select class="form-control col-md-6" name="status" id="status" value=" <?= set_value('status'); ?>" required >
										<option value="Aktif">Aktif</option>
										<option value="Tidak Aktif">Tidak Aktif</option>
									</select> <br>
								<small class="text-danger">
	                                <?php echo form_error('status') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_bank">Account Bank</label>
								<input class="form-control" type="text" name="nama_bank" id="nama_bank" value=" <?= set_value('nama_bank'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('nama_bank') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="rekening_bank">Rekening Bank</label>
								<input class="form-control" type="text" name="rekening_bank" id="rekening_bank" value=" <?= set_value('rekening_bank'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('rekening_bank') ?>
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