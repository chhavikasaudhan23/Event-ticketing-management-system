<?php
include '../../backend/config/db.php';
session_start();

$user_id   = $_SESSION['user_id'] ?? 0;
$user_name = $_SESSION['user_name'] ?? '';
$today = date('Y-m-d');

/* ===== CATEGORY LOGIC (UNCHANGED) ===== */
function getCategoryKey($title){
    $title = strtolower($title);

    if (strpos($title,'music')!==false || strpos($title,'dj')!==false || strpos($title,'concert')!==false || strpos($title,'band')!==false || strpos($title,'stand-up')!==false)
        return 'music';

    if (strpos($title,'cricket')!==false || strpos($title,'football')!==false || strpos($title,'tournament')!==false || strpos($title,'league')!==false)
        return 'sports';

    if (strpos($title,'yoga')!==false || strpos($title,'wellness')!==false || strpos($title,'meditation')!==false || strpos($title,'zumba')!==false)
        return 'wellness';

    if (strpos($title,'workshop')!==false || strpos($title,'seminar')!==false || strpos($title,'bootcamp')!==false || strpos($title,'coding')!==false || strpos($title,'training')!==false)
        return 'education';

    if (strpos($title,'corporate')!==false || strpos($title,'business')!==false || strpos($title,'startup')!==false)
        return 'corp';

    if (strpos($title,'exhibition')!==false || strpos($title,'expo')!==false || strpos($title,'fair')!==false)
        return 'expo';
    if(strpos($title,'social')!==false || strpos($title,'community')!==false || strpos($title,'ngo')!==false || strpos($title,'empowerment')!==false || strpos($title,'charity')!==false || strpos($title,'awareness')!==false || strpos($title,'donation')!==false || strpos($title,'women')!==false)
        return 'social';

    if (strpos($title,'college')!==false || strpos($title,'youth')!==false || strpos($title,'fest')!==false || strpos($title,'campus')!==false)
        return 'college';

    if (strpos($title,'dance')!==false || strpos($title,'art')!==false || strpos($title,'cultural')!==false || strpos($title,'drama')!==false)
        return 'cultural';

    return 'other';
}

function getCategoryUI($key){
    return [
        'music'     => ['Music & Entertainment','🎵','cat-music'],
        'sports'    => ['Sports & Fitness','🏏','cat-sports'],
        'wellness'  => ['Fitness & Wellness','🧘','cat-wellness'],
        'education' => ['Technical & Educational','🎓','cat-edu'],
        'corp'      => ['Corporate & Business','🏢','cat-corp'],
        'expo'      => ['Exhibition & Trade Shows','🛍️','cat-expo'],
        'social'    => ['Social & Community','🤝','cat-social'],
        'college'   => ['College & Youth Events','🎉','cat-college'],
        'cultural'  => ['Cultural & Arts','🎭','cat-cultural'],
        'other'     => ['Other Events','✨','cat-other']
    ][$key];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Events</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins,sans-serif}
body{background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);color:#fff}

/* NAVBAR */
.navbar{
    display:flex;justify-content:space-between;align-items:center;
    padding:16px 40px;
    background:rgba(255,255,255,.12);
    backdrop-filter:blur(14px);
}
.nav-left,.nav-right{
    display:flex;
    gap:22px;
    align-items:center;
}
.logo{
    font-size:20px;
    font-weight:600;
}
.navbar a{color:#fff;text-decoration:none;margin-right:18px}

/* CATEGORY SECTION */
.cat-wrap{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
    gap:15px;
    padding:25px 40px;
}
.cat-card{
    background:rgba(255,255,255,.18);
    border-radius:16px;
    padding:18px 10px;
    text-align:center;
    cursor:pointer;
    transition:.3s;
}
.cat-card span{font-size:22px;display:block}
.cat-card:hover{transform:translateY(-5px);background:rgba(255,255,255,.35)}

/* EVENTS */
.events{
    padding:25px 40px 60px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:25px;
}
.event-card{
    background:rgba(255,255,255,.18);
    border-radius:22px;
    padding:20px;
    box-shadow:0 20px 45px rgba(0,0,0,.35);
    transition:.3s;
}
.event-card:hover{transform:translateY(-8px)}

.badge{
    display:inline-block;
    padding:5px 14px;
    border-radius:20px;
    font-size:12px;
    margin:5px 3px;
    font-weight:600;
}
.expired{background:#ff7675;color:#000}
.upcoming{background:#00e0a8;color:#000}

/* CATEGORY COLORS */
.cat-music{background:#ff7675;color:#000}
.cat-sports{background:#55efc4;color:#000}
.cat-wellness{background:#81ecec;color:#000}
.cat-edu{background:#74b9ff;color:#000}
.cat-corp{background:#ffeaa7;color:#000}
.cat-expo{background:#fdcb6e;color:#000}
.cat-social{background:#a29bfe;color:#000}
.cat-college{background:#fab1a0;color:#000}
.cat-cultural{background:#fd79a8;color:#000}
.cat-other{background:#dfe6e9;color:#000}

.btn{
    display:block;text-align:center;
    padding:12px;border-radius:14px;
    margin-top:15px;text-decoration:none;font-weight:600;
}
.btn-book{background:#00e0a8;color:#000}
.btn-disabled{background:#999;color:#000}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
   <div class="nav-left"> 
        <div class="logo">🎫 Event Management</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="events.php">Events</a>
        <?php if($user_id): ?> <a href="my-bookings.php">My Tickets</a><?php endif; ?>
    </div>
    <div class="nav-right">
        <?php if($user_id): ?>
            👤 <?= htmlspecialchars($user_name) ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- CATEGORY FILTER (ALL PRESENT) -->
<div class="cat-wrap">
    <div class="cat-card" onclick="filterEvents('all')"><span>✨</span>All</div>
    <div class="cat-card" onclick="filterEvents('music')"><span>🎵</span>Music & Entertainment</div>
    <div class="cat-card" onclick="filterEvents('sports')"><span>🏏</span>Sports</div>
    <div class="cat-card" onclick="filterEvents('wellness')"><span>🧘</span>Wellness & Fitness</div>
    <div class="cat-card" onclick="filterEvents('education')"><span>🎓</span>Education</div>
    <div class="cat-card" onclick="filterEvents('corp')"><span>🏢</span>Corporate & Business</div>
    <div class="cat-card" onclick="filterEvents('expo')"><span>🛍️</span>Exhibition</div>
    <div class="cat-card" onclick="filterEvents('social')"><span>🤝</span>Social & Community</div>
    <div class="cat-card" onclick="filterEvents('college')"><span>🎉</span>College & Youth Events</div>
    <div class="cat-card" onclick="filterEvents('cultural')"><span>🎭</span>Cultural & Arts</div>
</div>

<!-- EVENTS LIST -->
<div class="events">
<?php
$q = mysqli_query($conn,"SELECT * FROM events ORDER BY date ASC");
while($e = mysqli_fetch_assoc($q)):
    $key = getCategoryKey($e['title']);
    $cat = getCategoryUI($key);
    $expired = ($e['date'] < $today);
?>
<div class="event-item" data-category="<?= $key ?>">
    <div class="event-card">

        <span class="badge <?= $expired?'expired':'upcoming' ?>">
            <?= $expired?'Expired':'Upcoming' ?>
        </span>
        <span class="badge <?= $cat[2] ?>">
            <?= $cat[1].' '.$cat[0] ?>
        </span>

        <h3><?= htmlspecialchars($e['title']) ?></h3>
        <p>📍 <?= htmlspecialchars($e['venue']) ?></p>
        <p>📅 <?= date('d M Y',strtotime($e['date'])) ?></p>
        <p>💰 ₹<?= $e['price'] ?></p>

        <?php if($expired): ?>
            <div class="btn btn-disabled">Event Closed</div>
        <?php else: ?>
            <a class="btn btn-book" href="<?= $user_id?'event-details.php?event_id='.$e['id']:'login.php' ?>">
                <?= $user_id?'View & Book':'Login to Book' ?>
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endwhile; ?>
</div>

<script>
function filterEvents(cat){
    document.querySelectorAll('.event-item').forEach(el=>{
        el.style.display = (cat==='all'||el.dataset.category===cat)?'block':'none';
    });
}
</script>

</body>
</html>
