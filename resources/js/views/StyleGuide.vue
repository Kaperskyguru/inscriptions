<template>
<div>
    <article>
        <header>Typographie</header>
        <section>
            <h1 class="title-styleguide">Titre</h1>
            <h1 class="title-large">Lorem Ipsum Dollar site</h1>
            <h1 class="title-primary">Lorem Ipsum Dollar site</h1>
            <h2 class="title-secondary">Lorem Ipsum Dollar site</h2>
            <h3 class="title-tertiary">Lorem Ipsum Dollar site</h3>
            <h4 class="title-headline">Lorem Ipsum Dollar site</h4>
        </section>
        <section>
            <h1 class="title-styleguide">Corps de texte</h1>
            <p class="text-body-display">Body Display</p>
            <p class="text-body">Corps de texte Vivamus eros mi, tempor in ultrices quis, sagittis vitae urna. Integer id augue ut odio ullamcorper euismod sit amet sit amet enim. Duis enim odio, consequat eget pharetra in, ultrices vitae est. Praesent ac vestibulum leo. Donec nisl eros, iaculis sed velit a, rutrum tristique lacus. Maecenas interdum urna ac erat consequat, vel suscipit ante lacinia. Quisque nec semper diam. Sed maximus nunc ac ipsum vehicula iaculis. Sed feugiat mauris erat, eget molestie orci consectetur sit amet. Nulla sagittis imperdiet velit, at elementum est. Quisque convallis, est id elementum finibus, risus tellus vehicula risus, vitae commodo odio libero in tellus. Nunc ut viverra ligula. Donec rutrum et augue at pharetra.</p>
            <p class="text-subhead">Subhead</p>
            <p class="text-footnote">Footnote</p>
            <p class="text-caption">Caption</p>
            <a href="#" class="text-link">Link</a>
        </section>
        <section>
            <h1 class="title-styleguide">Callout</h1>
            <p><a href="#" class="callout-main">Lorem Ipsum</a></p>
        </section>
    </article>
    <article>
        <header>Bouton</header>
        <section class="btn-container">
            <button class="btn btn-primary" disabled>Précedent</button>
            <button class="btn btn-primary">Suivant</button>
        </section>
        <section class="btn-container">
            <button class="btn btn-secondary" disabled>Précedent</button>
            <button class="btn btn-secondary">Suivant</button>
        </section>
    </article>
    <article>
        <header>Formulaires</header>
        <section>
        <form>
            <div class="form-group">
                <div class="floating-label-container">
                    <input id="user_email" class="form-text-field-input" type="email" @change="isEmpty">
                    <label class="floating-label" for="user_email">Courriel</label>
                </div>

            </div>
            <div class="form-group">
                 <div class="floating-label-container">
                    <input id="user_password" class="form-text-field-input" type="password">
                    <label class="floating-label" for="user_password">Mot de passe</label>
                </div>
            </div>
            <div class="form-group form-group-error">
                 <div class="floating-label-container">
                    <input id="user_name" class="form-text-field-input" type="text">
                    <label class="floating-label" for="user_name">Nom*</label>
                 </div>
                 <p class="form-msg-error">Mon Message Erreur</p>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Soumettre</button>
            </div>
            </form>
        </section>
    </article>
</div>

</template>
<script>
    export default {
        // methods: {
        //     isEmpty() {
        //         if(this.value.length > 0){
        //             this.parent.classList.add("form-group");
        //         } else {
        //             document.getElementById("myDiv").classList.remove("form-group");
        //         }
        //     },
        // }
    }
</script>
<style lang="scss" scoped>

header {
    background-color:#212529;
    padding:120px 60px 60px 120px;
    color:white;
    /* Typographie */
    font-family: 'Roboto';
    font-style: normal;
    font-weight: bold;
    font-size: 56px;
    line-height: 66px;
    text-transform: capitalize;
    margin:0 0 12rem 0;

}
section {
    padding:0 12rem 6rem 12rem;
}
.title-styleguide {

font-family: 'Roboto';
font-style: normal;
font-weight: bold;
font-size: 30px;
line-height: 35px;
/* identical to box height */

/* lightblue */
color: #C6D0EB;

}

</style>

