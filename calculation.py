import os
def file_len(fname):
    with open(fname) as f:
        for i, l in enumerate(f):
            pass
    return i + 1
# print(file_len("users.php"))
number = 0
for root, dirs, files in os.walk("."):
   for name in files:
      number +=file_len(os.path.join(root, name))
   for name in dirs:
      number+= file_len(os.path.join(root, name))

print(number)
