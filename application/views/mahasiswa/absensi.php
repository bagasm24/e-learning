        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 font-weight-bold mb-4 text-primary"><?= $judul; ?></h1>

            <div class="col-lg-12">
                    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-3" data-toggle="modal"
                    data-target="#Absensi" href="">Absensi
                    </a>
                <!-- ==<div class="tab-content responsive">
                    <div class="tab-pane active" id="absensi">
                        <div class="card shadow mb-4">
                            <div class="table-responsive">
                                <div class="card-body">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mata Kuliah</th>
                                                <th>Dosen</th>
                                                <th>Tanggal</th>
                                                <th>Kehadiran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
												date_default_timezone_set('Asia/Jakarta');
                                                $i = 1;
                                                foreach ($matkul as $mat) :
                                            ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $mat['matkul']; ?></td>
                                                <td><?= $mat['nama_dosen']; ?></td>
                                                <td><?= date("d F Y"); ?></td>
                                                <td>
                                                    <div id="btn_approval_<?= $mat['id_matkul']; ?>">
                                                        <?php if($absenhari > 0) {
															echo "Hadir";
															} else { ?>
                                                        <button type="button" class="btn btn-primary"
                                                            onclick="approve('<?= $mat['id_matkul']; ?>')">Hadir</button>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                                $i++;
                                            	endforeach;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

        </div>
        <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->
        
        <!-- Absensi -->
        <div class="modal fade" id="Absensi" tabindex="-1" aria-labelledby="AbsensiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-primary" id="AbsensiLabel">Input Presensi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" action="<?= base_url('mahasiswa/absensi'); ?>">
                <div class="modal-body">
                    <div class="row mx-auto">
                        <div class="col">
                            <div class="form-group">
                                <label for="formGroupExampleInput">NPM</label>
                                <input type="text" class="form-control" id="npm" name="npm"
                                    value="<?= set_value('npm'); ?>" placeholder="">
                                <?= form_error('npm', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Mata Kuliah</label>
                        <select class="form-control" id="exampleFormControlSelect2" name="kode_matkul">
                            <?php foreach($matkul as $mat) : ?>
                            <option value="<?= $mat['kode_matkul']; ?>"><?= $mat['matkul']; ?>
                                (<?= $mat['kode_matkul']; ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button onclick="getLocation()">Get Location</button>
	                    <p id="demo"></p>

	                <script>
                    function getLocation() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(showPosition);
                        } else {
                            alert("Geolocation is not supported by this browser.");
                        }
                    }

                    function showPosition(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;
                        document.getElementById("demo").innerHTML = "Latitude: " + latitude + "<br>Longitude: " + longitude;
                    }
                    </script>      
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
        <!-- Akhir Absensi -->

        <script>
function approve(id) {
    const approveAbsen = (data) => {
        $.ajax({
            url: "<?= base_url('mahasiswa/approveAbsensi'); ?>",
            type: 'post',
            data: data,
            dataType: "JSON",
            success: function(datas) {
                // document.location.href = "<?= base_url('mahasiswa/absensi'); ?>";
                var tombol = '#btn_approval_' + datas;
                $(tombol).children().hide();
                $(tombol).html('Kehadiran tersimpan');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Kesalahan saat melakukan absensi');
            }
        });
    };

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((pos) => {
            approveAbsen({
                id: id,
                latitude: `${pos.coords.latitude}`,
                longitude: `${pos.coords.longitude}`
            })
        });
    } else {
        approveAbsen({
            id: id,
        })
    }
}
        </script>
