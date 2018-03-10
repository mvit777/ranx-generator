# create a git repository here
git init
git add .
git commit -m "first commit"

# create a branch pointing to some existing remote repository
# Example github
git checkout -b github

# u know switched to the newly created branch github
git branch -a # to check

# merge changes from master
git merge master

# add a remote repo for this branch
# Ex. 
git remote add origin https://github.com/mvit777/m2_ext.git

# switch back to master
git checkout master

# repeat operation for each remote repository u want to map to a branch

