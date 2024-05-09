<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
*, *:before, *:after {
  box-sizing: border-box;
}


li a {
  text-decoration: none;
  color: #1ab188;
  transition: .5s ease;
}
li a:hover {
  color: #179b77;
}

.form {
  background: rgba(19, 35, 47, 0.9);
  padding: 10px;
  max-width: 80%;
  margin: 0 auto 0 auto;
  border-radius: 4px;
  box-shadow: 0 4px 10px 4px rgba(19, 35, 47, 0.3);
}

.tab-group {
  list-style: none;
  padding: 0;
  margin: 0;
}
.tab-group:after {
  content: "";
  display: table;
  clear: both;
}
.tab-group li a {
  display: block;
  text-decoration: none;
  padding: 15px;
  /* background: rgba(160, 179, 176, 0.25); */
  color: #a0b3b0;
  font-size: 20px;
  float: left;
  width: 50%;
  text-align: center;
  cursor: pointer;
  transition: .5s ease;
}
.tab-group li a:hover {
  background: #179b77;
  color: #ffffff;
}
.tab-group .active a {
  background: #1ab188;
  color: #ffffff;
}
.tab-left{
    
}



</style>
<body>
<div class="form">
      
      <!-- <ul class="tab-group">
        <li class="tab active"><a href="#signup">Application Form</a></li>
        <li class="tab"><a href="#login">Requested Certificate</a></li>
      </ul> -->

      <ul class="tab-group">
        <li class="tab-left active"><a href="#ApplicationForm">Application Form</a></li>
        <li class="tab-right"><a href="#Requested Certificate">Requested Certificate</a></li>
      </ul>
      
</div>
</body>
</html>