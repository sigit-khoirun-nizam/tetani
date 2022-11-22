<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

    <div id="wrapper">

        <div id="content-wrapper">

            <div class="container-fluid">

                <a href="<?php echo site_url('datatahun') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
                <h4 class="page-title">Edit Tahun</h4>

                <div class="card mb-3">
                    <div class="card-body">

                        <?php
                        //create form
                        $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
                        echo form_open('', $attributes);
                        ?>

                        <div class="form-group">
                            <label for="nama tahun">Nama Tahun</label>
                            <input type="hidden" class="form-control" id="id" name="id" value=" <?= $data_tahun->id; ?>">
                            <input type="text" class="form-control" id="nama_tahun" name="nama_tahun" value=" <?= $data_tahun->nama_tahun; ?>">
                            <small class="text-danger">
                                <?php echo form_error('nama_tahun') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="status tahun">Status</label>
                            <select class="form-control" id="status_tahun" name="status_tahun">
                                <option value="Pilih" selected disabled>Pilih</option>
                                <option value="Aktif" <?php if ($data_tahun->status_tahun == "Aktif") : echo "selected";
                                                        endif; ?>>Aktif</option>
                                <option value="Tidak Aktif" <?php if ($data_tahun->status_tahun == "Tidak Aktif") : echo "selected";
                                                            endif; ?>>Tidak Aktif</option>
                            </select>
                            <small class="text-danger">
                                <?php echo form_error('status_tahun') ?>
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