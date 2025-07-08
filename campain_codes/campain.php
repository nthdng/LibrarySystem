<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<!-- Instead of -->

<div class="nav">
  <ul>
    <li><a href="/">Home</a></li>
  </ul>
</div>

<style>

.container {
  padding-left: 20px;
  padding-right: 20px;
  width: 960px;
}


.button {
  background-color: #4CAF50;
  color: white;
}
.alert {
  background-color: #4CAF50;
}

</style>



<?php foreach ($posts as $post): ?>
  <div>
    <h2><?= $post['title'] ?></h2>
    <?php if ($post['is_featured']) echo '<span>★</span>'; ?>
  </div>
<?php endforeach; ?>

<a href="#" onclick="submitForm()">Submit</a>



<!-- Use -->

<nav aria-label="Main Navigation">
  <ul>
    <li><a href="/">Home</a></li>
  </ul>
</nav>


<style>

.container {
  padding-inline: 1.25rem;
  max-width: 60rem;
  width: 100%;
}

:root {
  --primary-color: #4CAF50;
  --text-light: #ffffff;
}

.button {
  background-color: var(--primary-color);
  color: var(--text-light);
}

.alert {
  background-color: var(--primary-color);
}


</style>

<?php foreach ($posts as $post): ?>
  <article class="post <?= $post['is_featured'] ? 'featured' : '' ?>">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <?php if ($post['is_featured']): ?>
      <span>★</span>
    <?php endif; ?>
  </article>
<?php endforeach; ?>


<button type="button" onclick="submitForm()">Submit</button>


</body>
</html>