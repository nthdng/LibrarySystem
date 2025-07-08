<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<!-- Instead of -->

<style>
@media (min-width: 600px) {
  .card-content {
    flex-direction: row;
  }
}
</style>

<?php
if ($user['role'] === 'admin') {
  echo 'Welcome, Admin.';
} elseif ($user['role'] === 'editor') {
  echo 'Welcome back, Editor.';
} else {
  echo 'Welcome, Guest.';
}
?>

<div id="alert-template" style="display: none">
  <div class="alert">⚠️ Something went wrong.</div>
</div>

<style>
.card {
  padding: 1rem;
}
.card__title {
  font-weight: bold;
}
.card:hover {
  background: #f0f0f0;
}
</style>

<?php
foreach ($users as $user) {
  if ($user['active']) {
    echo "<p>{$user['name']}</p>";
  }
}
?>

<div onclick="toggle(this)">
  <strong>What is this?</strong>
  <div style="display: none">This is hidden content revealed on click.</div>
</div>

<style>
.alert {
  padding-top: 1rem;
  padding-bottom: 1rem;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
  border-width: 1px;
  border-style: solid;
  border-color: red;
}
</style>

<?php
sendEmail('user@example.com', 'Welcome!', 'Thanks for signing up.');
?>

<style>
button {
  width: 44px;
  background-color: #00adf3;
}

@media (any-pointer: fine) {
  button { width: 30px; }
}

@supports (color: oklch(0.7 0.185 232)) {
  button { background-color: oklch(0.7 0.185 232); }
}
</style>

<style>
.card {
  padding-top: 1rem;
  padding-bottom: 1rem;
  padding-left: 2rem;
  padding-right: 2rem;
}
</style>


<!-- Use -->

<style>
.card {
  container-type: inline-size;
}

@container (min-width: 600px) {
  .card-content {
    flex-direction: row;
  }
}
</style>

<?php
$roles = [
  'admin' => 'Welcome, Admin.',
  'editor' => 'Welcome back, Editor.',
  'user' => 'Hello, User!'
];

echo $roles[$user['role']] ?? 'Welcome, Guest.';
?>

<template id="alert-template">
  <div class="alert">⚠️ Something went wrong.</div>
</template>

<style>
.card {
  padding: 1rem;

  &__title {
    font-weight: bold;
  }

  &:hover {
    background: #f0f0f0;
  }
}
</style>

<?php
foreach ($users as $user) {
  if (!$user['active']) continue;
  echo "<p>{$user['name']}</p>";
}
?>

<details>
  <summary>What is this?</summary>
  <p>This is hidden content revealed on click.</p>
</details>

<style>
.alert {
  padding: 1rem 1.5rem;
  border: 1px solid red;
}
</style>

<?php
sendEmail(
  to: 'user@example.com',
  subject: 'Welcome!',
  body: 'Thanks for signing up.'
);
?>

<style>
button {
  width: if(media(any-pointer: fine): 30px; else: 44px);
  background-color: if(supports(color: oklch(0.7 0.185 232)): oklch(0.7 0.185 232); else: #00adf3);
}
</style>

<style>
.card {
  padding-block: 1rem;
  padding-inline: 2rem;
}
</style>


</body>
</html>