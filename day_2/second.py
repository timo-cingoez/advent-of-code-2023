with open('input.txt') as file:
    games = (line.rstrip() for line in file)
    games = list(line for line in games if line)

powerSum = 0
for game in games:
    minColors = {'red' : 0, 'green' : 0, 'blue' : 0}

    sets = game.split(': ')[1].split('; ')
    for set in sets:
        for subSet in set.split(', '):
            count, color = subSet.split(' ')
            if int(count) > minColors[color]:
                minColors[color] = int(count)

    powerSum += minColors['red'] * minColors['green'] * minColors['blue']

print(powerSum)
