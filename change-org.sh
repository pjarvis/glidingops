find . -maxdepth 1 -type f -name '*.php' | xargs sudo sed -i  's/$org=0/$org=6/g'
