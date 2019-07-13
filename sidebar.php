<? require_once('functions.php'); ?>
<nav class="col-md-2 d-none d-md-block sidebar bg-light">
	<div class="sidebar-sticky">
		<ul class="nav flex-column border-bottom">
			<li class="nav-item">
				<span class="nav-link username">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
						<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
						<circle cx="12" cy="7" r="4"></circle>
					</svg>
<?
if(hasLoggedIn())
{
	echo $_SESSION["user_name"];
}
else
{
	echo "Guest";
}
?>
				</span>
			</li>
			<li class="nav-item">
				<span class="nav-link username">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
						<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
						<polyline points="9 22 9 12 15 12 15 22"></polyline>
					</svg>
<?
if(hasLoggedIn())
{
	echo $_SESSION["class_id"];
}
else
{
	echo "None";
}
?>
				</span>
			</li>
			<li class="nav-item">
				<span class="nav-link">
<? if(isset($_SESSION["user_id"])) { ?>
					<a class="btn btn-primary btn-sm" href="signout.php" role="button">Sign out</a>
<? } else { ?>
					<a class="btn btn-primary btn-sm" href="signin.php" role="button">Sign in</a>
<? } ?>
				</span>
			</li>
		</ul>
<? if(hasLoggedIn() && $_SESSION["permission_level"] == 0) { ?>
		<ul class="nav flex-column">
			<li class="nav-item">
				<a class="nav-link <? if($page == "assignments.php") { echo active; } ?>" href="assignments.php">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard">
						<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
						<rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
					</svg>
					Assignments
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <? if($page == "statistics.php") { echo active; } ?>" href="statistics.php?class=<?= $_SESSION["class_id"] ?>&user=<?= $_SESSION["user_id"] ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2">
						<line x1="18" y1="20" x2="18" y2="10"></line>
						<line x1="12" y1="20" x2="12" y2="4"></line>
						<line x1="6" y1="20" x2="6" y2="14"></line>
					</svg>
					Statistics
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <? if($page == "settings.php") { echo active; } ?>" href="settings.php">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
						<circle cx="12" cy="12" r="3"></circle>
						<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
					</svg>
					Settings
				</a>
			</li>
		</ul>
<? } else if(hasLoggedInAsAdmin()) { ?>
		<ul class="nav flex-column">
			<li class="nav-item">
				<a class="nav-link <? if($page == "admin_classes.php") { echo active; } ?>" href="admin_classes.php">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
						<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
						<polyline points="9 22 9 12 15 12 15 22"></polyline>
					</svg>
					Classes
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <? if($page == "admin_assignments.php") { echo active; } ?>" href="admin_assignments.php">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard">
						<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
						<rect x="8" y="2" width="8" height="4" rx="1" ry="1">
						</rect>
					</svg>
					Assignments
				</a>
			</li>
		</ul>
<? } ?>
	</div>
</nav>
