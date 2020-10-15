<table class="table table-striped table-hover" id="sample-table-2">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Phone Number</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($conductors as $key => $value) {
        ?>
            <tr>
                <td><?=$value['firstname']." ".$value['secondname']." ".$value['thirdname']?></td>
                <td><?=$value['phonenumber']?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>