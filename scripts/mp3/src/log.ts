import chalk from 'chalk'

const colours = [
  chalk.red,
  chalk.green,
  chalk.yellow,
  chalk.blue,
  chalk.magenta,
  chalk.cyan,
]

const logStr = (id?: number) => {
  if (id === undefined) return chalk.bold('[M]')

  const func = colours[id % colours.length]
  return func(`[${id}]`)
}

export const log = (str: string, id?: number) => {
  const i = logStr(id)
  console.log(`${i} ${str}`) // tslint:disable-line no-console
}
