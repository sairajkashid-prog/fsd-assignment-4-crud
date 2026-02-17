<?php
$conn = mysqli_connect("localhost", "root", "", "crud_db");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

/* DELETE */
if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM users WHERE id=".$_GET['delete']);
    header("Location: index.php");
}

/* EDIT */
$edit_mode = false;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $result = mysqli_query($conn,"SELECT * FROM users WHERE id=".$_GET['edit']);
    $row = mysqli_fetch_assoc($result);
}

/* INSERT */
if (isset($_POST['submit'])) {
    mysqli_query($conn,"INSERT INTO users (name,email,phone,age)
    VALUES ('$_POST[name]','$_POST[email]','$_POST[phone]','$_POST[age]')");
    header("Location: index.php");
}

/* UPDATE */
if (isset($_POST['update'])) {
    mysqli_query($conn,"UPDATE users SET
    name='$_POST[name]',
    email='$_POST[email]',
    phone='$_POST[phone]',
    age='$_POST[age]'
    WHERE id=$_POST[id]");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>⚡ CYBER TERMINAL CRUD</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

<style>
body {
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#000;
    color:white;
    cursor:none;
}

/* PARTICLES */
#particles-js {
    position:fixed;
    width:100%;
    height:100%;
    z-index:-1;
}

/* CURSOR TRAIL */
.cursor-dot {
    position:fixed;
    width:10px;
    height:10px;
    background:#00ffff;
    border-radius:50%;
    pointer-events:none;
    box-shadow:0 0 15px #00ffff;
    z-index:9999;
}

/* CONTAINER */
.container { width:85%; margin:40px auto; }

.card {
    background:rgba(20,20,20,0.9);
    padding:30px;
    border-radius:20px;
    box-shadow:0 0 40px #ff00ff55;
    margin-bottom:30px;
    backdrop-filter:blur(15px);
}

/* TYPING HEADING */
.typing {
    text-align:center;
    font-size:30px;
    color:#00ffff;
    border-right:3px solid #00ffff;
    white-space:nowrap;
    overflow:hidden;
    width:0;
    animation: typing 4s steps(30,end) forwards, blink 0.7s infinite;
}
@keyframes typing {
    from { width:0 }
    to { width:100% }
}
@keyframes blink {
    50% { border-color:transparent }
}

/* INPUT */
.input-group { margin-bottom:15px; }
.input-group input {
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#111;
    color:white;
    box-shadow:0 0 10px #ff00cc33;
}
.input-group input:focus {
    outline:none;
    box-shadow:0 0 20px #00ffffaa;
}

/* BUTTON */
button {
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:linear-gradient(90deg,#ff00cc,#00ffff);
    color:black;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}
button:hover {
    transform:scale(1.07);
    box-shadow:0 0 25px #00ffff;
}

/* SEARCH */
.search-box input {
    width:50%;
    padding:10px;
    border-radius:25px;
    border:none;
    background:#111;
    color:white;
}
.search-box input:focus {
    width:65%;
    box-shadow:0 0 20px #ff00cc;
}

/* TABLE */
table { width:100%; border-collapse:collapse; }
th, td { padding:12px; text-align:center; }
th {
    background:linear-gradient(90deg,#ff00cc,#00ffff);
    color:black;
}
tr:hover {
    background:#111;
    transform:scale(1.02);
    box-shadow:0 0 15px #00ffff;
}

/* ACTION ICONS */
a { font-size:18px; margin:0 5px; transition:0.3s; }
.edit-btn { color:#00ffff; }
.delete-btn { color:#ff4444; }
a:hover { transform:scale(1.2); }
</style>

<script>
/* SEARCH */
function searchUser() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("table tr");
    rows.forEach((row, index) => {
        if(index===0) return;
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

/* CURSOR TRAIL */
document.addEventListener("mousemove", function(e){
    let dot = document.createElement("div");
    dot.classList.add("cursor-dot");
    dot.style.left = e.pageX + "px";
    dot.style.top = e.pageY + "px";
    document.body.appendChild(dot);
    setTimeout(()=>{ dot.remove(); }, 500);
});
</script>

</head>
<body>

<div id="particles-js"></div>

<div class="container">

<div class="card">
<h2 class="typing">⚡ CYBERPUNK USER MANAGER ⚡</h2>

<form method="POST">
<?php if ($edit_mode): ?>
<input type="hidden" name="id" value="<?php echo $_GET['edit']; ?>">
<?php endif; ?>

<div class="input-group">
<input type="text" name="name" placeholder="Full Name"
value="<?php echo $edit_mode ? $row['name'] : ''; ?>" required>
</div>

<div class="input-group">
<input type="text" name="email" placeholder="Email"
value="<?php echo $edit_mode ? $row['email'] : ''; ?>" required>
</div>

<div class="input-group">
<input type="text" name="phone" placeholder="Phone"
value="<?php echo $edit_mode ? $row['phone'] : ''; ?>" required>
</div>

<div class="input-group">
<input type="number" name="age" placeholder="Age"
value="<?php echo $edit_mode ? $row['age'] : ''; ?>" required>
</div>

<?php if ($edit_mode): ?>
<button type="submit" name="update"><i class="fa fa-pen"></i> UPDATE</button>
<?php else: ?>
<button type="submit" name="submit"><i class="fa fa-plus"></i> SAVE</button>
<?php endif; ?>
</form>
</div>

<div class="card">
<h2>⚡ USER RECORDS ⚡</h2>

<div class="search-box" style="text-align:center;margin-bottom:15px;">
<input type="text" id="searchInput" onkeyup="searchUser()" placeholder="🔍 Search user...">
</div>

<?php
$result = mysqli_query($conn,"SELECT * FROM users");
if(mysqli_num_rows($result)>0){
echo "<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Age</th>
<th>Action</th>
</tr>";
while($r=mysqli_fetch_assoc($result)){
echo "<tr>
<td>".$r['id']."</td>
<td>".$r['name']."</td>
<td>".$r['email']."</td>
<td>".$r['phone']."</td>
<td>".$r['age']."</td>
<td>
<a class='edit-btn' href='?edit=".$r['id']."'><i class='fa fa-edit'></i></a>
<a class='delete-btn' href='?delete=".$r['id']."' onclick=\"return confirm('Delete?')\"><i class='fa fa-trash'></i></a>
</td>
</tr>";
}
echo "</table>";
}
?>
</div>
</div>

<script>
particlesJS("particles-js", {
  "particles": {
    "number": {"value": 80},
    "color": {"value": ["#ff00cc","#00ffff","#ffffff"]},
    "shape": {"type": "circle"},
    "opacity": {"value": 0.7},
    "size": {"value": 3},
    "line_linked": {
      "enable": true,
      "distance": 150,
      "color": "#00ffff",
      "opacity": 0.4,
      "width": 1
    },
    "move": {"enable": true,"speed": 3}
  }
});
</script>

</body>
</html>
