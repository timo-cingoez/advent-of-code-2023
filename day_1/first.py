with open('input.txt') as file:
    lines = (line.rstrip() for line in file)
    lines = list(line for line in lines if line)

lineNums = []
for line in lines:
    lineNums.append([])
    for char in line:
        if char.isnumeric():
            lineNums[-1].append(char)

sum = 0
for idx, num in enumerate(lineNums):
    if len(lineNums[idx]) > 1:
        sum += int(num[0] + '' + num[-1])
    else:
        sum += int(num[0] + '' + num[0])

print(sum)
