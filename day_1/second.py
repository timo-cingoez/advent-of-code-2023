with open('input.txt') as file:
    lines = (line.rstrip() for line in file)
    lines = list(line for line in lines if line)

subs = {
    "twone" : "21",
    "sevenine" : "79",
    "oneight" : "18",
    "threeight" : "38",
    "nineight" : "98",
    "fiveight" : "58",
    "eighthree" : "83",
    "eightwo" : "82",
    "one" : "1",
    "two" : "2",
    "three" : "3",
    "four" : "4",
    "five" : "5",
    "six" : "6",
    "seven" : "7",
    "eight" : "8",
    "nine" : "9"
}

lineNums = []
for line in lines:
    lineNums.append([])

    for k, v in subs.items():
        line = line.replace(k, v)

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
