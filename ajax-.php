<?php
session_start();

    //Defining database variables
    $host = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "vmprovisioner";
    $username = $_SESSION['username'];
    
    //Creating Database Connection
    

        if (isset($_POST['key'])){

            $conn = new mysqli($host, $dbuser, $dbpass, $dbname);
                


            if ($_POST['key'] == 'getrowdata'){
                $rowid = $conn->real_escape_string($_POST['rowid']);
                
                $sql = $conn->query("SELECT vmname, username, vmvcpu, vmmem, vmdisk, notes, admincomments, command, vmstatus, time FROM vm WHERE id='$rowid'");
                $data = $sql->fetch_array();
                $jsonarray = array(
                    'vmname' => $data['vmname'],
                    'vmvcpu' => $data['vmvcpu'],
                    'vmmem' => $data['vmmem'],
                    'vmdisk' => $data['vmdisk'],
                    'notes' => $data['notes'],
                    'admincomments' => $data['admincomments'],
                    'command' => $data['command'],
                    'vmstatus' => $data['vmstatus'],
                    'time' => $data['time'],
                );
                exit(json_encode($jsonarray));
            }

            if ($_POST['key'] == 'populatetable'){
                $start = $conn->real_escape_string($_POST['start']);
                $limit = $conn->real_escape_string($_POST['limit']);

                $sql = $conn->query("SELECT id, vmname, username, vmvcpu, vmmem, vmdisk, vmstatus, time FROM vm WHERE username='$username' LIMIT $start, $limit");
                if($sql->num_rows > 0){
                    $response = "";
                    while($data = $sql->fetch_array()){
                         $response .= '                    
                    <tr>
                        <td>'.$data["id"].'</td>
                        <td id="vmname_'.$data["id"].'">'.$data["vmname"].'</td>
                        <td id="username_'.$data["id"].'">'.$data["username"].'</td>
                        <td id="vmvcpu_'.$data["id"].'">'.$data["vmvcpu"].'</td>
                        <td id="vmmem_'.$data["id"].'">'.$data["vmmem"].'</td>
                        <td id="vmdisk_'.$data["id"].'">'.$data["vmdisk"].'</td>
                        <td id="vmstatus_'.$data["id"].'">'.$data["vmstatus"].'</td>
                        <td id="time_'.$data["id"].'">'.$data["time"].'</td>
                        <td>
                        <input type="button" onclick="vieworedit('.$data["id"].', \'edit\')" value="Edit" class="btn btn-primary">
                        <input type="button" onclick="vieworedit('.$data["id"].', \'view\')" value="View command" class="btn btn-success">
                        <input type="button" onclick="deleterow('.$data["id"].')" value="Delete" class="btn btn-danger">
                        </td>
                    </tr>
                    ';
                    }
                    exit($response);
                }else
                exit('reachedmax');
            }
            
            $rowid = $conn->real_escape_string($_POST['rowid']); 
            if($_POST['key'] == 'deleterow'){
                $conn->query("DELETE FROM vm WHERE id='$rowid'");
                exit('The Row has been Deleted!');
            }            
            
            $vmname = $conn->real_escape_string($_POST['vmname']);
            $username = $_SESSION['username'];
            $vmvcpu = $conn->real_escape_string($_POST['vmvcpu']);
            $vmmem = $conn->real_escape_string($_POST['vmmem']);
            $vmdisk = $conn->real_escape_string($_POST['vmdisk']);
            $notes = $conn->real_escape_string($_POST['notes']);
            $admincomments = $conn->real_escape_string(isset($_POST['admincomments']));
            $vmstatus = $conn->real_escape_string(isset($_POST['vmstatus']));
            $command = "./esxi-vm-create -n $vmname -c $vmvcpu -m $vmmem -s $vmdisk";

           

            if ($_POST['key'] == 'updaterow') {
                $sql = $conn->query("SELECT vmname From vm Where vmname = '$vmname'");
                if($sql->num_rows > 0){
                    exit("A VM already exists by this name");}
                else{
               $conn->query("UPDATE vm SET vmname = '$vmname', vmvcpu = '$vmvcpu', vmmem = '$vmmem', vmdisk = '$vmdisk', notes = '$notes', admincomments= '$admincomments', command = '$command', vmstatus = '$vmstatus' WHERE id='$rowid'");
               exit('VM Request Updated!');}
            }
            if ($_POST['key'] == 'addnew') {
                $sql = $conn->query("SELECT vmname From vm Where vmname = '$vmname'");
                if($sql->num_rows > 0){
                    exit("A VM already exists by this name");}
                else{            
                    $conn->query("INSERT INTO vm (vmname, username, vmvcpu, vmmem, vmdisk, notes, admincomments, command, vmstatus) 
                    VALUES ('$vmname', '$username', '$vmvcpu', '$vmmem', '$vmdisk', '$notes','', '$command', 'Requested')");
                    exit('VM has been requested!');
                
                }
            }
        }
?>