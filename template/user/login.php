<div class="form-content">
    <div class="wrapper">
        <form autocomplete="off">
            <h1>Connection</h1>
            <div class="input-box">
                <input type="text" name="pseudo" placeholder="Votre pseudo ou email" autocomplete="new-username" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Votre password" autocomplete="new-password" required>
                <i class="fas fa-lock"></i>
                <div class="view">
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-eye-slash"></i>
                </div>
            </div>
            <button type="submit" name="validate" class="btn">Se connecter</button>
            <div class="register-link">
                <p>Pas de compte ? <a href="<?= $router->url('register')?>">S'inscrire</a></p>
            </div>
        </form>
    </div>
</div>