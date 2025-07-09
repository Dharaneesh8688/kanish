
  <!-- feedback -->
   <div class="container my-5">
  <section id="feedback" class="py-5 bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Customer Appreciations</h2>
      <a href="all-feedbacks.php" class="btn btn-primary btn-sm">View All</a>
    </div>

    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
  <?php if (!empty($feedbacks)): ?>
    <?php 
      // Shuffle feedbacks randomly on each page load
      shuffle($feedbacks); 
      foreach(array_chunk($feedbacks, 3) as $chunkIndex => $chunk): 
    ?>
      <div class="carousel-item <?php if ($chunkIndex === 0) echo 'active'; ?>">
        <div class="row">
          <?php foreach($chunk as $fb): ?>
            <div class="col-md-4 mb-3">
              <div class="card h-100 shadow-sm">
                <div class="card-body">
                  <div class="d-flex align-items-center mb-3">
                    <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile" width="40" height="40" class="rounded-circle border">
                    <div class="ms-3">
                      <h6 class="mb-0"><?php echo htmlspecialchars($fb['name']); ?></h6>
                      <small class="text-muted">
                        <?php echo date("d M Y"); ?>
                        <i class="bi bi-globe ms-1"></i>
                      </small>
                    </div>
                  </div>
                  <p class="card-text">
                    <?php
                    $text = htmlspecialchars($fb['message']);
                    if (strlen($text) > 120) {
                      echo substr($text, 0, 120) . "... <span class='text-primary'>Read More</span>";
                    } else {
                      echo $text;
                    }
                    ?>
                  </p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="carousel-item active">
      <div class="row">
        <div class="col">
          <div class="card h-100 text-center p-5">
            <p>No feedback yet. Be the first to share your experience!</p>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>


      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <div class="text-center mt-4">
      <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#feedbackModal">
        Add Feedback
      </button>
    </div>
  </div>
</section>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="feedbackFormModal">
        <div class="modal-header">
          <h5 class="modal-title">Share Your Feedback</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Your Feedback</label>
            <textarea name="message" class="form-control" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
        </div>
      </form>
    </div>
  </div>
</div>
  </div>


