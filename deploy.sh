#!/bin/bash

# 1️⃣ Lancer le build
echo "🚀 Lancement de npm run build..."
npm run build

# Vérifie si le build a réussi
if [ $? -ne 0 ]; then
  echo "❌ Build échoué, arrêt du script."
  exit 1
fi

# 2️⃣ Ajouter tous les changements à git
git add .

# 3️⃣ Demander le message de commit
read -p "✏️ Entrez le message de commit : " commit_message

# Si le message est vide, générer un commit par défaut
if [ -z "$commit_message" ]; then
  commit_message="Commit automatique"
fi

# 4️⃣ Faire le commit
git commit -m "$commit_message"

# 5️⃣ Demander si l'utilisateur veut push
read -p "📤 Voulez-vous pousser les changements sur le dépôt distant ? (o/n) : " push_choice
if [[ "$push_choice" == "o" || "$push_choice" == "O" ]]; then
  git push
  echo "✅ Changements poussés avec succès."
else
  echo "⚠️ Changements non poussés."
fi
