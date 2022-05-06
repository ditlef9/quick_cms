# Quick CMS

# 1. Licence
https://github.com/ditlef9/quick_cms/blob/master/_docs/GNU%20GENERAL%20PUBLIC%20LICENSE.txt

# 2. How to Install Quick CMS on local Windows
1.1 Visual Studio VC<br />
Download and install Visual Studio 2010 : VC 2010 vcredist_2010_sp1_x64.exe from https://wampserver.aviatechno.net/files/vcpackages/vcredist_2010_sp1_x64.exe<br />
Download and install Visual Studio 2012 : VC 11 vcredist_x64 from https://wampserver.aviatechno.net/files/vcpackages/vcredist_2012_upd4_x64.exe<br />
Download and install Visual Studio 2013 : VC 2013 from https://wampserver.aviatechno.net/files/vcpackages/vcredist_2013_upd5_x64.exe<br />
Download and install Visual Studio 2015 : VC 2015 from https://wampserver.aviatechno.net/files/vcpackages/vcredist_2019_x64.exe<br /><br />

Make sure you have all VCRedist packages installed by running this tool: http://wampserver.aviatechno.net/files/tools/check_vcredist.exe

1.2 Wampserver<br />
Download an install latest version of Wampserver from https://www.wampserver.com/en/<br />
I installed Wampserver to `C:\Users\user\wamp64`.

1.3 Git<br />
Download and install Git from https://git-scm.com/download/win

1.4 Checkout project<br />
Open program Git Bash (C:\Program Files\Git\git-bash.exe). Browse to your www directory and clone the project with the following commands:<br />
`cd C:\Users\user\wamp64\www`<br />
`git clone https://github.com/europa9/quick_cms.git`

1.5 Create database<br />
Open phpMyAdmin at localhost/phpmyadmin/. Default username is root without password. Create a database named quick.

1.6 Install Quick CMS<br />
Open http://localhost/quick_cms and follow the instructions to install the CMS.


# 3. How to fetch changes
Open Git Bash and write the following commands:<br />
`cd C:\Users\user\wamp64\www`<br />
`git pull`

# 4. How to Commit changes
Open Git Bash and write the following commands:<br />
`cd C:\Users\user\wamp64\www`<br />
`git pull`<br />
`git add -A`<br />
`git commit -m "description of changes"`<br />
`git push`


