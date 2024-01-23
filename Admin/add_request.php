<?php
require_once 'utils/is_login.php';
require_once '../Models/EmployeeModel.php';
require_once '../Models/ServicesModel.php';
require_once '../Models/AppointmentModel.php';
$head_title = 'Add Request';
$page_title = 'Add Request';
$employeeModel = new EmployeeModel();
$employee = $employeeModel->getEmployeeById($_SESSION['id']);
$servicesModel = new ServicesModel();
$services = $servicesModel->getAllServices();
$appointmentModel = new AppointmentModel();
$appointments =  $appointmentModel->getApprovedFutureAppointments();
$servicesModel = new ServicesModel();
$packages = $servicesModel->getAllPackages();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Add Request Form<?php echo $_SESSION['id'];  ?></title>
  <?php require 'components/head.html' ?>
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>
<style>
  .container {
    height: 100%;
  }

  .packages input {
    display: inline;
  }

  .tot span {
    font-size: 20px;
    font-weight: 800;
    margin-left: 750px;
  }

  .tot input {
    font-size: 25px;
    height: 50px;
    background-color: rgba(0, 0, 0, 0);
    width: 140px;
    border: none;
    font-weight: 700;
    text-align: right;
    margin-left: 750px;
  }

  .tet {
    margin-top: 26px;
    margin-left: 750px;
    width: 140px;
    height: 50px;
    box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.20);
    position: absolute;
  }

  th {
    top: 0;
    z-index: 2;
    position: sticky;
    background-color: white;
  }

  td {
    font-weight: 500;
  }

  .total {

    position: relative;

    margin-top: -70px;
    margin-left: 77%;
    z-index: 1;
  }

  .input-field label {
    font-size: 15px;
  }

  .tbl-scroll {

    overflow-y: scroll;
    height: 220px;
  }

  #request_form {

    min-height: 1050px;
  }
</style>

<body>

  <?php require_once 'components/header.html' ?>

  <!-- ======= Sidebar ======= -->
  <?php require 'components/sidebar.html';
  ?>

  <main id="main" class="main">


    <div class="pagetitle">
      <h1>Request Form</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Request Form</li>
          <li class="breadcrumb-item active">Add Request Form</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-md-12">

          <div class="card" style="overflow: auto;">
            <div class="card-body">
              <hr>
              <div class="container">
                <header>Add Request Form</header>
                <form id="request_form" enctype="multipart/form-data">
                  <select name="" id="appointment-select" class="form-select form-select-sm">
                    <option selected>Select Appointments</option>
                    <?php foreach ($appointments as $appointment) :
                      $patient_name = $appointment->patient->getFullName();
                    ?>
                      <option value="<?php echo $appointment->id ?>"><?php echo "Appointment  #$appointment->id: $patient_name " ?></option>
                    <?php endforeach ?>
                  </select>
                  <div class="form first">
                    <div class="details personal">
                      <label>Date</label>
                      <input type="text" name="request_date" class="form-control" id="request_date" readonly>
                      <script>
                        var d = new Date()
                        var yr = d.getFullYear();
                        var month = d.getMonth() + 1

                        if (month < 10) {
                          month = '0' + month
                        }
                        var date = d.getDate();
                        if (date < 10) {
                          date = '0' + date
                        }
                        var c_date = yr + "-" + month + "-" + date;

                        document.getElementById("request_date").value = c_date;
                      </script>
                      <div class="fields">
                        <div class="input-field">
                          <label>Lastname</label>
                          <input type="text" id='last_name' name="request_lastname" placeholder="Enter your Lastame" required>
                        </div>
                        <div class="input-field">
                          <label>Firstname</label>
                          <input type="text" id='first_name' name="request_firstname" placeholder="Enter your Firstname" required>
                        </div>
                        <div class="input-field">
                          <label>Sex</label>
                          <select id='gender' required name="request_gender">
                            <option disabled selected>Select Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                        </div>
                        <div class="input-field">
                          <label>Date of Birth</label>
                          <input type="date" id="dob" name="request_birthdate" placeholder="Enter birth date" required>
                        </div>
                        <div class="input-field">
                          <label>Age</label>
                          <input type="number" onmousemove="FindAge()" id="age" name="request_age" placeholder="Your age " required>
                        </div>
                        <div class="input-field">
                          <label>Mobile Number</label>
                          <input type="tel" id='mobile_number' name="request_phone" pattern="[0-9]{11}" pmaxlength="11" oninput="validateNumber(event)" placeholder="Enter mobile number" required>
                        </div>

                        <div class="input-field">
                          <label>Province</label>
                          <select required name="request_province" id="province">
                            <option disabled selected>Select Province</option>


                          </select>
                        </div>
                        <div class="input-field">
                          <label>City</label>
                          <select required name="request_city" id="city">
                            <option disabled selected>Select City</option>


                          </select>
                        </div>
                        <div class="input-field">
                          <label>Barangay</label>
                          <select required name="request_barangay" id="barangay">
                            <option disabled selected>Select Barangay</option>


                          </select>
                        </div>
                        <div class="input-field">
                          <label>Purok</label>
                          <input type="text" id="purok" name="request_purok" placeholder="Enter your Purok" required>
                        </div>
                        <div class="input-field">
                          <label>Subdivision/Street Name</label>
                          <input type="text" id="subdivision" name="request_subdivision" placeholder="Enter your Subdivision/Street Name">
                        </div>

                        <div class="input-field">
                          <label>Building/ House Number</label>
                          <input type="text" id="house_no" name="request_house_no" placeholder="Enter your Building/ House Number">
                        </div>
                      </div>
                    </div>


                    <div class="container">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="card">
                            <div class="card-body">
                              <br>

                              <div class="row mb-3">


                                <div class="col-lg-12">
                                  <div class="row">
                                    <div class="col">
                                      <h5>Select service</h5>
                                    </div>
                                    <div class="col row">
                                      <select onchange="fillPackageServices()" id="package-select">
                                        <option disabled selected>Select Package</option>
                                        <?php
                                        foreach ($packages as $package) {
                                        ?>
                                          <option value="<?php echo $package['id'] ?>"><?php echo $package['name'] ?></option>
                                        <?php } ?>

                                        </option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-sm-6">
                                      <br>
                                      <div class="form-group">
                                        <select class="form-select" id="test1" name="request_test[]" aria-label="Default select example">
                                          <option disabled selected>Choose Test</option>
                                          <?php
                                          foreach ($services as $service) {
                                          ?>
                                            <option value="<?php echo $service->id ?>" data-price="<?php echo $service->price ?>"><?php echo $service->name ?></option>
                                          <?php } ?>

                                          </option>
                                        </select>
                                      </div>
                                    </div>



                                    <div class="col-sm-6">
                                      <br>
                                      <div class="form-group">
                                        <select class="form-select" id="test2" name="request_test[]" aria-label="Default select example">
                                          <option disabled selected>Choose Test</option>
                                          <?php
                                          foreach ($services as $service) {
                                          ?>
                                            <option value="<?php echo $service->id ?>" data-price="<?php echo $service->price ?>"><?php echo $service->name ?></option>
                                          <?php } ?>

                                          </option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <select class="form-select" id="test3" name="request_test[]" aria-label="Default select example">
                                          <option disabled selected>Choose Test</option>
                                          <?php
                                          foreach ($services as $service) {
                                          ?>
                                            <option value="<?php echo $service->id ?>" data-price="<?php echo $service->price ?>"><?php echo $service->name ?></option>
                                          <?php } ?>

                                          </option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <select class="form-select" id="test4" name="request_test[]" aria-label="Default select example">
                                          <option disabled selected>Choose Test</option>
                                          <?php
                                          foreach ($services as $service) {
                                          ?>
                                            <option value="<?php echo $service->id ?>" data-price="<?php echo $service->price ?>"><?php echo $service->name ?></option>
                                          <?php } ?>

                                          </option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <select class="form-select" id="test5" name="request_test[]" aria-label="Default select example">
                                          <option disabled selected>Choose Test</option>
                                          <?php
                                          foreach ($services as $service) {
                                          ?>
                                            <option value="<?php echo $service->id ?>" data-price="<?php echo $service->price ?>"><?php echo $service->name ?></option>
                                          <?php } ?>

                                          </option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <select class="form-select" id="test6" name="request_test[]" aria-label="Default select example">
                                          <option disabled selected>Choose Test</option>
                                          <?php
                                          foreach ($services as $service) {
                                          ?>
                                            <option value="<?php echo $service->id ?>" data-price="<?php echo $service->price ?>"><?php echo $service->name ?></option>
                                          <?php } ?>

                                          </option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <select class="form-select" id="test7" name="request_test[]" aria-label="Default select example">
                                          <option disabled selected>Choose Test</option>
                                          <?php
                                          foreach ($services as $service) {
                                          ?>
                                            <option value="<?php echo $service->id ?>" data-price="<?php echo $service->price ?>"><?php echo $service->name ?></option>
                                          <?php } ?>

                                          </option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <select class="form-select" id="test8" name="request_test[]" aria-label="Default select example">
                                          <option disabled selected>Choose Test</option>
                                          <?php
                                          foreach ($services as $service) {
                                          ?>
                                            <option value="<?php echo $service->id ?>" data-price="<?php echo $service->price ?>"><?php echo $service->name ?></option>
                                          <?php } ?>

                                        </select>
                                        </option>
                                      </div>
                                    </div>

                                  </div>
                                </div>
                              </div>

                            </div>
                            <input hidden id="user_id" type='number' name="user_id" value=<?php echo $_SESSION['id'] ?>>
                          </div>


                          <div class="row">
                            <div class="col-sm-4" style="margin-left:auto">
                              <label for="" class="row" style="  font-size: 30px;">Total Amount</label>
                              <label class="col" for="" style=" font-size: 40px;">&#x20B1;</label>
                              <input class="col-sm-10" type="text" style="border:none; font-size: 30px; text-indent: 45px;" id="total" name="request_amount" class="form-control" value="0.00" readonly>
                              <button type="submit" id="third" name="submit" class="btn btn-primary">Submit</button>
                            </div>

                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
        </form>
      </div>
      </div>
      </div>

      </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="../assets/js/script3.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="../assets/js/main.js"></script>

  <script>
    const appointments = <?php echo json_encode($appointments); ?>;
    const user_id = <?php echo $_SESSION['id'] ?>;
    const packages = <?php echo json_encode($packages) ?>;
    const services = <?php echo json_encode($services) ?>;
  </script>
  <script src="../assets/js/admin/add_request.js"></script>



</body>

</html>