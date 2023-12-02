with open('input.txt') as file:
    games = (line.rstrip() for line in file)
    games = list(line for line in games if line)

maxColors = {'red' : 12, 'green' : 13, 'blue' : 14};

gameIdSum = 0
for game in games:
    isPossible = True

    sets = game.split(': ')[1].split('; ')
    for set in sets:
        for subSet in set.split(', '):
            count, color = subSet.split(' ')
            if int(count) > maxColors[color]:
                isPossible = False
                break

    if isPossible:
        id = game.split(' ')[1].replace(':', '')
        gameIdSum += int(id)

print(gameIdSum)
