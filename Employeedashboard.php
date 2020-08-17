<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role']!="Employee"){
  header("location:login.php");}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="title icon" href="images/Virtual Machine Provisioner.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <title>VMProvisioner Dashboard</title>
  </head>
  <body>
    
    
    <!-- navbar -->
    <nav class="navbar navbar-expand-md navbar-light">
      <button class="navbar-toggler ml-auto mb-2 bg-light" type="button" data-toggle="collapse" data-target="#myNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="myNavbar">
        <div class="container-fluid">
          <div class="row">
            <!-- sidebar -->
            <div class="col-xl-2 col-lg-3 col-md-4 sidebar fixed-top">
              <a href="#" class="navbar-brand text-white d-block mx-auto text-center py-3 mb-4 bottom-border">VMProvisioner</a>
              <div class="bottom-border pb-3">
                <img src="images/employee-xxl.png" width="50" class="rounded-circle mr-3">
                <a href="#" class="text-white"><?php echo $_SESSION['username']?> </a>
              </div>
              <ul class="navbar-nav flex-column mt-4">
                <li class="nav-item"><a href="engineerdashboard.php" class="nav-link text-white p-3 mb-2 current"><i class="fas fa-home text-light fa-lg mr-3"></i>Dashboard</a></li>
              </ul>
            </div>
            <!-- end of sidebar -->

            <!-- top-nav -->
            <div class="col-xl-10 col-lg-9 col-md-8 ml-auto bg-dark fixed-top py-2 top-navbar">
              <div class="row align-items-center">
                <div class="col-md-4">
                  <h4 class="text-light text-uppercase mb-0">Dashboard</h4>
                </div>
                <div class="col-md-5">
                </div>
                <div class="col-md-3">
                  <ul class="navbar-nav">
                    <li class="nav-item ml-md-auto"><a href="#" class="nav-link" data-toggle="modal" data-target="#sign-out"><i class="fas fa-sign-out-alt text-danger fa-lg"></i></a></li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- end of top-nav -->
          </div>
        </div>
      </div>
    </nav>
    <!-- end of navbar -->

    <!-- modal -->
    <div class="modal fade" id="sign-out">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Want to leave?</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            Press logout to leave
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">Stay Here</button>
            <a href="logout.php" type="button" class="btn btn-danger" data-dismiss="logout.php">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- end of modal -->


<!-- tables -->

<div class="container" style="margin-top: 150px;">

  <div id="tablemanager" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Create a VM</h2>
        </div>
        <div class="modal-body">
          <div id="editcontent">
          <h3>VM Name</h3>
          <input type="text" class="form-control" placeholder="vmname" id="vmname"><br>
          <h3>Virtual CPU Count</h3>
          <select class="form-control" id="vmvcpu">
            <option selected>1</option>
            <option>2</option>
            <option>3</option>
          </select><br>
          <h3>Memory (GB)</h3>
          <select class="form-control" id="vmmem">
            <option>0.5</option>
            <option>1</option>
            <option>2</option>
            <option>4</option>
            <option>8</option>
          </select><br>
          <h3>Storage (GB)</h3>
          <select class="form-control" id="vmdisk">
            <option>1</option>
            <option>2</option>
            <option>4</option>
            <option selected>8</option>
            <option>16</option>
            <option>32</option>
            <option>64</option>
          </select><br>
          <textarea class="form-control" id="notes" placeholder="notes"></textarea><br>          
          <input type="hidden" id="editrowid" value="0">
          </div>
          <div id="showcontent" style="display:none;">

              <h3>Command</h3>
              <textarea class="form-control" id="command" readonly></textarea><br>
              <h3>Notes</h3>
              <div id="notesview" style="overflow-y: scroll; height: 100px"></div>
              <hr>            
              <h3>Admin's Comments</h3>
              <div id="admincommentsview" style="overflow-y: scroll; height: 100px"></div>
              <hr> 
              <h3>Status</h3>
              <textarea class="form-control" id="vmstatusview" readonly></textarea><br>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-primary" data-dismiss="modal" value="Close" id="closebtn" style="display: none;">
          <input type="button" id="managebtn" onclick="managedata('addnew')" value="Save" class="btn btn-success">
        </div>
    </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <input style="float: right" type="button" class="btn btn-success" id="addnew" value="Add New">
      <br><br>
      <table class="table table-hover table-bordered">
        <thead>
          <td>ID</td>
          <td>VM Name</td>
          <td>VM User</td>
          <td>VM CPU</td>
          <td>VM Memory</td>
          <td>VM Disk</td>
          <td>Status</td>
          <td>Time of Request</td>
          <td>Options</td>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>
</div>


    <!-- end of tables -->

    <!-- footer -->
    <footer>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xl-10 col-lg-9 col-md-8 ml-auto">
            <div class="row border-top pt-3">
              <div class="col-lg-6 text-center">
                <ul class="list-inline">
                  <li class="list-inline-item mr-2">
                    <a href="#" class="text-dark">Project by Hayden Roberts</a>
                  </li>
                </ul>
              </div>
              <div class="col-lg-6 text-center">
                <p>&copy; 2020 Copyright.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- end of footer -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script type="text/javascript" src="javascripts/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="javascripts/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
      $("#addnew").on('click', function(){
        $("#tablemanager").modal('show');
      });
      $("#tablemanager").on('hidden.bs.modal', function (){
        $(".modal-title").html('Create a VM');
        $("#showcontent").fadeOut();
        $("#editcontent").fadeIn();
        $("#editrowid").val(0);
        $("#vmname").val("");
        $("#vmvcpu").val("");
        $("#vmmem").val("");
        $("#vmdisk").val("");
        $("#notes").val("");
        $("#admincomments").val("");
        $("#vmstatus").val("");
        $("#closebtn").fadeOut();
        $("#managebtn").attr('value', 'Add new').attr('onclick', "managedata('addnew')").fadeIn();
      });
      populatetable(0, 10);
    });

    function deleterow(rowid){
      if (confirm('Are you sure?')){
        $.ajax({
          url: 'ajax-.php',
          method: 'POST',
          dataType: 'text',
          data: {
            key: 'deleterow',
            rowid: rowid
          }, success: function(response){
            $("#vmname_"+rowid).parent().remove();
            alert(response);
          }
        });
      }
    }

    function vieworedit(rowid, type){
      $.ajax({
          url: 'ajax-.php',
          method: 'POST',
          dataType: 'json',
          data: {
            key: 'getrowdata',
            rowid: rowid
          }, success: function(response){
            if (type == "view"){              
              $("#showcontent").fadeIn();
              $("#editcontent").fadeOut();
              $("#managebtn").fadeOut();
              $("#notesview").html(response.notes);
              $("#command").html(response.command);
              $("#admincommentsview").html(response.admincomments);
              $("#vmstatusview").html(response.vmstatus);
              $("#closebtn").fadeIn();

            }else{
              $("#editcontent").fadeIn();
              $("#editrowid").val(rowid);              
              $("#showcontent").fadeOut();
              $("#vmname").val(response.vmname);
              $("#vmvcpu").val(response.vmvcpu);
              $("#vmmem").val(response.vmmem);
              $("#vmdisk").val(response.vmdisk);
              $("#notes").val(response.notes);
              $("#admincomments").val(response.admincomments)
              $("#command").val(response.command);
              $("#vmstatus").val(response.vmstatus);
              $("#closebtn").fadeOut();          
              $("#managebtn").attr('value', 'Save Changes').attr('onclick', "managedata('updaterow')");
            }
              $(".modal-title").html(response.vmname);
              $("#tablemanager").modal('show');

          }
        });
    }

    function populatetable(start, limit){
      $.ajax({
        url: 'ajax-.php',
        method: 'POST',
        dataType: 'text',
        data: {
          key: 'populatetable',
          start: start,
          limit: limit
        }, success: function(response){
              if (response != "reachedmax"){
                $('tbody').append(response);
                start += limit;
                populatetable(start, limit);
              } else
              $(".table").DataTable();
          }
      });
    }

    function managedata(key){
      var vmname = $("#vmname");
      var vmvcpu = $("#vmvcpu");
      var vmmem = $("#vmmem");
      var vmdisk = $("#vmdisk");
      var notes = $("#notes");
      var admincomments = $("#admincomments");
      var command = $("#command");
      var vmstatus = $("#vmstatus");
      var editrowid = $("#editrowid");

      if (isNotEmpty(vmname)){
        $.ajax({
          url: 'ajax-.php',
          method: 'POST',
          dataType: 'text',
          data: {
            key: key,
            vmname: vmname.val(),
            vmvcpu: vmvcpu.val(),
            vmmem: vmmem.val(),
            vmdisk: vmdisk.val(),
            notes: notes.val(),
            admincomments: admincomments.val(),    
            command: command.val(),
            vmstatus: vmstatus.val(),
            rowid: editrowid.val()
          }, 
          success: function(response){
            if(response != "VM Request Updated!")
              alert(response);
              else{
                $("#vmname_"+editrowid.val()).html(vmname.val());
                $("#vmvcpu_"+editrowid.val()).html(vmvcpu.val());
                $("#vmmem_"+editrowid.val()).html(vmmem.val());
                $("#vmdisk_"+editrowid.val()).html(vmdisk.val());
                $("#notes_"+editrowid.val()).html(notes.val());
                $("#admincomments_"+editrowid.val()).html(admincomments.val());
                $("#command_"+editrowid.val()).html(command.val());
                $("#vmstatus_"+editrowid.val()).html(vmstatus.val());
                $("#tablemanager").modal('hide');
                $("#managebtn").attr('value', 'Add').attr('onclick', "managedata('addnew')");
              }
            
              
          }
        });
      }

      function isNotEmpty(caller) {
            if (caller.val() == '') {
                caller.css('border', '1px solid red');
                return false;
            } else
                caller.css('border', '');

            return true;
        }

    }
    </script>

  </body>
</html>






