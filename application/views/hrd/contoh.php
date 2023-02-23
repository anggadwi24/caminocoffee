<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table>
                <thead>
                    <th>Tanggal</th>
                    <th>Absen In</th>
                    <th>Absen Out</th>
                </thead>
                <tbody>
                    <?php 
                        foreach($record->result() as $row){
                            echo "<tr>
                                    <td>".$row->date."</td>
                                    <td>".$row->absen_in."</td>
                                    <td>".$row->absen_out."</td>

                                </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>