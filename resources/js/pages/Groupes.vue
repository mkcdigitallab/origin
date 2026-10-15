<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ students: Array })
const groupesGeneres = ref([])
const form = useForm({
  fullname: '',
  sexe: '',
  niveau: '',
})

function ajouter() {
  form.post('/students', {
    onSuccess: () => form.reset(),
  })
}

const afficherListe = ref(false)

function basculerListe() {
  afficherListe.value = !afficherListe.value
}

const nbGarcons = computed(
  () => props.students.filter((s) => s.sexe === 'M').length,
)

const nbFilles = computed(
  () => props.students.filter((s) => s.sexe === 'F').length,
)

const tailleGroupe = ref(3)

function genererGroupes() {
 if (tailleGroupe.value < 2) {
    alert('Un groupe doit contenir au moins 2 étudiants.')
    return
  }

  if (props.students.length < 2) {
    alert('Il faut au moins 2 étudiants pour créer des groupes.')
    return
  }

  const garcons = props.students.filter((s) => s.sexe === 'M')
  const filles = props.students.filter((s) => s.sexe === 'F')

    if (garcons.length === 0 || filles.length === 0) {
    alert('Chaque groupe doit contenir au moins un garçon et une fille.')
    return
  }

  if (props.students.length < tailleGroupe.value) {
    alert('Le nombre d’étudiants est insuffisant pour créer un groupe de cette taille.')
    return
  }
  
}
</script>

<template>
  <div class="page">
    <!-- En-tête -->
    <header class="entete">
      <div class="entete-contenu">
        <span class="surtitre">ESPACE PÉDAGOGIQUE</span>
        <h1>Gestion des étudiants</h1>
        <p>Organisez votre classe et créez des groupes de travail équilibrés.</p>
      </div>

      <div class="entete-decoration" aria-hidden="true">
        <div class="decoration-cercle cercle-1"></div>
        <div class="decoration-cercle cercle-2"></div>
        <div class="decoration-cercle cercle-3"></div>
      </div>
    </header>

    <!-- Statistiques générales -->
    <section class="statistiques">
      <article class="stat-carte">
        <div class="stat-icone icone-total" aria-hidden="true">E</div>
        <div>
          <p class="stat-label">Total étudiants</p>
          <p class="stat-valeur">{{ props.students.length }}</p>
        </div>
      </article>

      <article class="stat-carte">
        <div class="stat-icone icone-garcons" aria-hidden="true">G</div>
        <div>
          <p class="stat-label">Garçons</p>
          <p class="stat-valeur">{{ nbGarcons }}</p>
        </div>
      </article>

      <article class="stat-carte">
        <div class="stat-icone icone-filles" aria-hidden="true">F</div>
        <div>
          <p class="stat-label">Filles</p>
          <p class="stat-valeur">{{ nbFilles }}</p>
        </div>
      </article>
    </section>

    <!-- 1. Ajout d'un étudiant -->
    <section class="carte">
      <div class="carte-entete">
        <div class="numero-section">01</div>

        <div>
          <h2>Ajouter un étudiant</h2>
          <p class="description">Renseignez les informations du nouvel étudiant.</p>
        </div>
      </div>

      <form @submit.prevent="ajouter" class="formulaire">
        <div class="champ champ-grand">
          <label for="fullname">Nom complet</label>
          <input
            id="fullname"
            v-model="form.fullname"
            type="text"
            placeholder="Ex. : Amadou Diallo"
            autocomplete="name"
          />
          <small v-if="form.errors.fullname" class="erreur">
            {{ form.errors.fullname }}
          </small>
        </div>

        <div class="champ">
          <label for="sexe">Sexe</label>
          <select id="sexe" v-model="form.sexe">
            <option value="" disabled>Sélectionner</option>
            <option value="M">Garçon</option>
            <option value="F">Fille</option>
          </select>
          <small v-if="form.errors.sexe" class="erreur">
            {{ form.errors.sexe }}
          </small>
        </div>

        <div class="champ">
          <label for="niveau">Niveau</label>
          <select id="niveau" v-model="form.niveau">
            <option value="" disabled>Sélectionner</option>
            <option value="1">Niveau 1</option>
            <option value="2">Niveau 2</option>
            <option value="3">Niveau 3</option>
          </select>
          <small v-if="form.errors.niveau" class="erreur">
            {{ form.errors.niveau }}
          </small>
        </div>

        <div class="formulaire-actions">
          <button
            type="submit"
            class="btn btn-principal"
            :disabled="form.processing"
          >
            <span v-if="form.processing" class="spinner"></span>
            {{ form.processing ? 'Enregistrement...' : '+ Ajouter l’étudiant' }}
          </button>
        </div>
      </form>
    </section>

    <!-- 2. Liste des étudiants -->
    <section class="carte">
      <div class="carte-entete carte-entete-liste">
        <div class="numero-section">02</div>

        <div class="titre-section">
          <h2>Liste des étudiants</h2>
          <p class="description">
            Consultez les étudiants enregistrés dans votre classe.
          </p>
        </div>

        <button
          type="button"
          class="btn btn-secondaire"
          @click="basculerListe"
        >
          {{ afficherListe ? 'Masquer la liste' : 'Voir les étudiants' }}
          <span class="fleche" :class="{ 'fleche-haut': afficherListe }">
            ↓
          </span>
        </button>
      </div>

      <div class="resume-liste">
        <span class="resume-element">
          <span class="point point-total"></span>
          {{ props.students.length }} étudiant(s)
        </span>

        <span class="separateur"></span>

        <span class="resume-element">
          <span class="point point-m"></span>
          {{ nbGarcons }} garçons
        </span>

        <span class="separateur"></span>

        <span class="resume-element">
          <span class="point point-f"></span>
          {{ nbFilles }} filles
        </span>
      </div>

      <div v-if="afficherListe" class="contenu-liste">
        <div v-if="props.students.length === 0" class="etat-vide">
          <div class="vide-icone">∅</div>
          <h3>Aucun étudiant pour le moment</h3>
          <p>Ajoutez votre premier étudiant à l'aide du formulaire ci-dessus.</p>
        </div>

        <div v-else class="table-wrapper">
          <table class="table-etudiants">
            <thead>
              <tr>
                <th>Étudiant</th>
                <th>Sexe</th>
                <th>Niveau</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="s in props.students" :key="s.id">
                <td>
                  <div class="identite">
                    <div
                      class="avatar"
                      :class="s.sexe === 'M' ? 'avatar-m' : 'avatar-f'"
                    >
                      {{ s.fullname.charAt(0).toUpperCase() }}
                    </div>

                    <span class="nom">{{ s.fullname }}</span>
                  </div>
                </td>

                <td>
                  <span
                    class="etiquette"
                    :class="
                      s.sexe === 'M' ? 'etiquette-m' : 'etiquette-f'
                    "
                  >
                    {{ s.sexe === 'M' ? 'Garçon' : 'Fille' }}
                  </span>
                </td>

                <td>
                  <span class="niveau-badge">
                    Niveau {{ s.niveau }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- 3. Génération des groupes -->
    <section class="carte">
      <div class="carte-entete">
        <div class="numero-section">03</div>

        <div>
          <h2>Générer les groupes</h2>
          <p class="description">
            Définissez la taille souhaitée pour chaque groupe.
          </p>
        </div>
      </div>

      <div class="generateur">
        <div class="champ champ-taille">
          <label for="taille">Étudiants par groupe</label>
          <input
            id="taille"
            v-model.number="tailleGroupe"
            type="number"
            min="2"
            class="input-taille"
          />
        </div>

        <button
          type="button"
          class="btn btn-principal btn-generer"
          @click="genererGroupes"
        >
          Générer les groupes
          <span aria-hidden="true">→</span>
        </button>
      </div>

      <div class="note-generateur">
        <span class="note-icone">i</span>
        <p>
          Les groupes seront constitués à partir des étudiants enregistrés.
        </p>
      </div>
    </section>

    <footer class="pied-page">
      <span>Gestion des étudiants</span>
      <span>Organisation · Collaboration · Réussite</span>
    </footer>
  </div>
</template>

<style scoped>
/* =========================================
   1. IDENTITÉ VISUELLE
========================================= */

.page {
  --primaire: #4f46e5;
  --primaire-fonce: #3730a3;
  --primaire-clair: #eef2ff;
  --texte: #172033;
  --texte-secondaire: #64748b;
  --bordure: #e5eaf2;
  --fond: #f5f7fb;
  --blanc: #ffffff;
  --garcon: #0284c7;
  --fille: #db2777;

  width: 100%;
  max-width: 1080px;
  margin: 0 auto;
  padding: 36px 24px 48px;
  color: var(--texte);
  font-family:
    Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  box-sizing: border-box;
}

.page *,
.page *::before,
.page *::after {
  box-sizing: border-box;
}

/* =========================================
   2. EN-TÊTE
========================================= */

.entete {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 210px;
  padding: 38px 42px;
  margin-bottom: 26px;
  overflow: hidden;
  border-radius: 20px;
  color: white;
  background: linear-gradient(120deg, #312e81 0%, #4f46e5 60%, #7c3aed 100%);
  box-shadow: 0 12px 32px rgb(79 70 229 / 15%);
}

.entete-contenu {
  position: relative;
  z-index: 2;
  max-width: 600px;
}

.surtitre {
  display: inline-block;
  margin-bottom: 14px;
  color: #c7d2fe;
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 2px;
}

.entete h1 {
  margin: 0 0 12px;
  font-size: clamp(1.8rem, 4vw, 2.45rem);
  font-weight: 750;
  line-height: 1.2;
  letter-spacing: -1.2px;
}

.entete p {
  max-width: 480px;
  margin: 0;
  color: #e0e7ff;
  font-size: 0.98rem;
  line-height: 1.7;
}

.entete-decoration {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
}

.decoration-cercle {
  position: absolute;
  border: 1px solid rgb(255 255 255 / 15%);
  border-radius: 50%;
}

.cercle-1 {
  top: -100px;
  right: -30px;
  width: 330px;
  height: 330px;
  background: rgb(255 255 255 / 4%);
}

.cercle-2 {
  top: -40px;
  right: 65px;
  width: 220px;
  height: 220px;
}

.cercle-3 {
  top: 20px;
  right: 125px;
  width: 100px;
  height: 100px;
  background: rgb(255 255 255 / 5%);
}

/* =========================================
   3. STATISTIQUES
========================================= */

.statistiques {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 18px;
  margin-bottom: 28px;
}

.stat-carte {
  display: flex;
  align-items: center;
  gap: 16px;
  min-width: 0;
  padding: 23px;
  background: var(--blanc);
  border: 1px solid var(--bordure);
  border-radius: 15px;
  box-shadow: 0 3px 12px rgb(15 23 42 / 3%);
  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
}

.stat-carte:hover {
  transform: translateY(-3px);
  box-shadow: 0 9px 24px rgb(15 23 42 / 7%);
}

.stat-icone {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  width: 49px;
  height: 49px;
  border-radius: 13px;
  font-size: 1.15rem;
  font-weight: 800;
}

.icone-total {
  color: #4f46e5;
  background: #eef2ff;
}

.icone-garcons {
  color: #0284c7;
  background: #e0f2fe;
}

.icone-filles {
  color: #db2777;
  background: #fce7f3;
}

.stat-label {
  margin: 0 0 5px;
  color: var(--texte-secondaire);
  font-size: 0.84rem;
  font-weight: 500;
}

.stat-valeur {
  margin: 0;
  font-size: 1.8rem;
  font-weight: 750;
  line-height: 1.1;
  letter-spacing: -0.7px;
}

/* =========================================
   4. CARTES ET TITRES
========================================= */

.carte {
  padding: 28px;
  margin-bottom: 22px;
  background: var(--blanc);
  border: 1px solid var(--bordure);
  border-radius: 17px;
  box-shadow: 0 4px 16px rgb(15 23 42 / 3%);
}

.carte-entete {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 27px;
}

.numero-section {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  width: 47px;
  height: 47px;
  border: 1px solid #e0e7ff;
  border-radius: 13px;
  color: var(--primaire);
  background: var(--primaire-clair);
  font-size: 0.9rem;
  font-weight: 800;
}

.carte h2 {
  margin: 0 0 6px;
  font-size: 1.12rem;
  font-weight: 750;
  letter-spacing: -0.25px;
}

.description {
  margin: 0;
  color: var(--texte-secondaire);
  font-size: 0.88rem;
  line-height: 1.6;
}

.carte-entete-liste {
  flex-wrap: wrap;
}

.titre-section {
  flex: 1;
  min-width: 180px;
}

/* =========================================
   5. FORMULAIRES
========================================= */

.formulaire {
  display: grid;
  grid-template-columns: minmax(200px, 2fr) minmax(140px, 1fr) minmax(140px, 1fr);
  gap: 20px;
}

.champ {
  display: flex;
  flex-direction: column;
  gap: 9px;
  min-width: 0;
}

.champ label,
.generateur label {
  color: #334155;
  font-size: 0.86rem;
  font-weight: 650;
}

.page input,
.page select {
  width: 100%;
  min-width: 0;
  min-height: 46px;
  padding: 11px 13px;
  border: 1px solid #dce3ed;
  border-radius: 9px;
  outline: none;
  color: var(--texte);
  background: #fff;
  font: inherit;
  font-size: 0.9rem;
  transition:
    border-color 0.2s ease,
    box-shadow 0.2s ease;
}

.page input::placeholder {
  color: #a0aabc;
}

.page input:hover,
.page select:hover {
  border-color: #b7c3d5;
}

.page input:focus,
.page select:focus {
  border-color: var(--primaire);
  box-shadow: 0 0 0 3px rgb(79 70 229 / 10%);
}

.formulaire-actions {
  grid-column: 1 / -1;
  padding-top: 3px;
}

/* =========================================
   6. BOUTONS
========================================= */

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  min-height: 44px;
  padding: 11px 17px;
  border: 1px solid transparent;
  border-radius: 9px;
  font: inherit;
  font-size: 0.87rem;
  font-weight: 650;
  cursor: pointer;
  transition:
    background 0.2s ease,
    border-color 0.2s ease,
    box-shadow 0.2s ease,
    transform 0.2s ease;
}

.btn:focus-visible {
  outline: 3px solid rgb(79 70 229 / 35%);
  outline-offset: 3px;
}

.btn:active {
  transform: scale(0.98);
}

.btn-principal {
  color: #fff;
  background: var(--primaire);
  border-color: var(--primaire);
  box-shadow: 0 3px 7px rgb(79 70 229 / 14%);
}

.btn-principal:hover:not(:disabled) {
  background: var(--primaire-fonce);
  border-color: var(--primaire-fonce);
  box-shadow: 0 5px 13px rgb(79 70 229 / 20%);
}

.btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.btn-secondaire {
  flex-shrink: 0;
  color: #334155;
  background: #fff;
  border-color: var(--bordure);
}

.btn-secondaire:hover {
  color: var(--primaire);
  background: #f8faff;
  border-color: #c7d2fe;
}

.fleche {
  display: inline-block;
  transition: transform 0.2s ease;
}

.fleche-haut {
  transform: rotate(180deg);
}

.spinner {
  width: 15px;
  height: 15px;
  border: 2px solid rgb(255 255 255 / 40%);
  border-top-color: #fff;
  border-radius: 50%;
  animation: tourner 0.7s linear infinite;
}

@keyframes tourner {
  to {
    transform: rotate(360deg);
  }
}

.erreur {
  color: #dc2626;
  font-size: 0.79rem;
  line-height: 1.4;
}

/* =========================================
   7. RÉSUMÉ DES ÉTUDIANTS
========================================= */

.resume-liste {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 15px;
  padding: 15px 17px;
  border: 1px solid #e9edf5;
  border-radius: 10px;
  background: #f8faff;
}

.resume-element {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #475569;
  font-size: 0.83rem;
  font-weight: 550;
}

.point {
  display: inline-block;
  width: 8px;
  height: 8px;
  flex-shrink: 0;
  border-radius: 50%;
}

.point-total {
  background: var(--primaire);
}

.point-m {
  background: var(--garcon);
}

.point-f {
  background: var(--fille);
}

.separateur {
  width: 1px;
  height: 18px;
  background: #dce3ed;
}

.contenu-liste {
  margin-top: 18px;
}

/* =========================================
   8. TABLEAU DES ÉTUDIANTS
========================================= */

.table-wrapper {
  width: 100%;
  overflow-x: auto;
  border: 1px solid var(--bordure);
  border-radius: 11px;
}

.table-etudiants {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.table-etudiants thead {
  background: #f8faff;
}

.table-etudiants th {
  padding: 14px 17px;
  border-bottom: 1px solid var(--bordure);
  color: #64748b;
  font-size: 0.76rem;
  font-weight: 750;
  letter-spacing: 0.4px;
  text-transform: uppercase;
  white-space: nowrap;
}

.table-etudiants td {
  padding: 13px 17px;
  border-bottom: 1px solid #edf0f5;
  font-size: 0.87rem;
}

.table-etudiants tbody tr:last-child td {
  border-bottom: none;
}

.table-etudiants tbody tr {
  transition: background 0.15s ease;
}

.table-etudiants tbody tr:hover {
  background: #fafbff;
}

.identite {
  display: flex;
  align-items: center;
  gap: 11px;
  min-width: 180px;
}

.avatar {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  width: 36px;
  height: 36px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 750;
  text-transform: uppercase;
}

.avatar-m {
  color: #0369a1;
  background: #e0f2fe;
}

.avatar-f {
  color: #be185d;
  background: #fce7f3;
}

.nom {
  color: #253047;
  font-weight: 650;
}

.etiquette,
.niveau-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 5px 10px;
  border-radius: 6px;
  font-size: 0.76rem;
  font-weight: 650;
  white-space: nowrap;
}

.etiquette-m {
  color: #0369a1;
  background: #e0f2fe;
}

.etiquette-f {
  color: #be185d;
  background: #fce7f3;
}

.niveau-badge {
  color: #475569;
  background: #f1f5f9;
}

/* =========================================
   9. ÉTAT VIDE
========================================= */

.etat-vide {
  padding: 36px 20px;
  border: 1px dashed #d8dfeb;
  border-radius: 12px;
  text-align: center;
  background: #fafbfe;
}

.vide-icone {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  margin: 0 auto 15px;
  border-radius: 14px;
  color: var(--primaire);
  background: var(--primaire-clair);
  font-size: 1.7rem;
}

.etat-vide h3 {
  margin: 0 0 8px;
  font-size: 1rem;
}

.etat-vide p {
  margin: 0;
  color: var(--texte-secondaire);
  font-size: 0.87rem;
  line-height: 1.6;
}

/* =========================================
   10. GÉNÉRATION DES GROUPES
========================================= */

.generateur {
  display: flex;
  align-items: flex-end;
  flex-wrap: wrap;
  gap: 16px;
}

.champ-taille {
  width: 210px;
}

.page input.input-taille {
  width: 100%;
}

.btn-generer {
  min-height: 46px;
}

.note-generateur {
  display: flex;
  align-items: flex-start;
  gap: 11px;
  margin-top: 22px;
  padding: 14px 15px;
  border: 1px solid #e0e7ff;
  border-radius: 10px;
  background: #f5f7ff;
}

.note-icone {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  width: 20px;
  height: 20px;
  border: 1.5px solid #6366f1;
  border-radius: 50%;
  color: #4f46e5;
  font-size: 0.75rem;
  font-weight: 800;
}

.note-generateur p {
  margin: 1px 0 0;
  color: #4b5563;
  font-size: 0.82rem;
  line-height: 1.6;
}

/* =========================================
   11. PIED DE PAGE
========================================= */

.pied-page {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
  padding: 22px 3px 0;
  color: #94a3b8;
  font-size: 0.77rem;
}

/* =========================================
   12. RESPONSIVE : TABLETTES
========================================= */

@media (max-width: 760px) {
  .page {
    padding: 24px 17px 35px;
  }

  .entete {
    min-height: 190px;
    padding: 30px 26px;
  }

  .cercle-1 {
    right: -180px;
  }

  .cercle-2 {
    right: -90px;
  }

  .cercle-3 {
    right: -20px;
  }

  .statistiques {
    gap: 12px;
  }

  .stat-carte {
    gap: 11px;
    padding: 17px 13px;
  }

  .stat-icone {
    width: 41px;
    height: 41px;
  }

  .stat-valeur {
    font-size: 1.55rem;
  }

  .stat-label {
    font-size: 0.77rem;
  }

  .carte {
    padding: 23px 20px;
  }

  .formulaire {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .champ-grand {
    grid-column: 1 / -1;
  }

  .formulaire-actions {
    grid-column: 1 / -1;
  }

  .carte-entete-liste .btn {
    width: 100%;
  }
}

/* =========================================
   13. RESPONSIVE : MOBILES
========================================= */

@media (max-width: 480px) {
  .page {
    padding: 17px 12px 28px;
  }

  .entete {
    min-height: 180px;
    padding: 27px 21px;
    margin-bottom: 17px;
    border-radius: 15px;
  }

  .entete h1 {
    font-size: 1.75rem;
    letter-spacing: -0.7px;
  }

  .entete p {
    font-size: 0.88rem;
  }

  .statistiques {
    grid-template-columns: 1fr;
    gap: 10px;
    margin-bottom: 19px;
  }

  .stat-carte {
    padding: 15px 17px;
  }

  .stat-valeur {
    font-size: 1.45rem;
  }

  .carte {
    padding: 20px 15px;
    margin-bottom: 16px;
    border-radius: 13px;
  }

  .carte-entete {
    align-items: flex-start;
    gap: 11px;
    margin-bottom: 22px;
  }

  .numero-section {
    width: 39px;
    height: 39px;
    border-radius: 10px;
    font-size: 0.78rem;
  }

  .carte h2 {
    font-size: 1rem;
  }

  .description {
    font-size: 0.81rem;
  }

  .formulaire {
    grid-template-columns: minmax(0, 1fr);
    gap: 17px;
  }

  .champ-grand {
    grid-column: auto;
  }

  .formulaire-actions {
    grid-column: auto;
  }

  .formulaire-actions .btn {
    width: 100%;
  }

  .resume-liste {
    gap: 11px;
    padding: 13px 11px;
  }

  .resume-element {
    font-size: 0.77rem;
  }

  .separateur {
    display: none;
  }

  .table-etudiants th,
  .table-etudiants td {
    padding: 12px 10px;
  }

  .table-etudiants th {
    font-size: 0.69rem;
  }

  .table-etudiants td {
    font-size: 0.8rem;
  }

  .identite {
    gap: 8px;
    min-width: 145px;
  }

  .avatar {
    width: 31px;
    height: 31px;
  }

  .generateur {
    align-items: stretch;
    flex-direction: column;
  }

  .champ-taille {
    width: 100%;
  }

  .btn-generer {
    width: 100%;
  }

  .pied-page {
    flex-direction: column;
    align-items: flex-start;
    gap: 7px;
  }
}

/* =========================================
   14. ACCESSIBILITÉ
========================================= */

@media (prefers-reduced-motion: reduce) {
  .page *,
  .page *::before,
  .page *::after {
    scroll-behavior: auto !important;
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style>